<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'items.alat'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pembayaran')) {
            if ($request->pembayaran === 'menunggu_verifikasi') {
                $query->where('status_pembayaran_denda', 'menunggu_verifikasi');
            } elseif ($request->pembayaran === 'ada_denda') {
                $query->where('denda', '>', 0);
            } elseif ($request->pembayaran === 'terverifikasi') {
                $query->where('status_pembayaran_denda', 'terverifikasi');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('items.alat', fn($a) => $a->where('nama', 'like', "%{$search}%"));
            });
        }

        $peminjaman = $query->paginate(15);

        $stats = [
            'total'                     => Peminjaman::count(),
            'menunggu'                  => Peminjaman::where('status', 'menunggu')->count(),
            'disetujui'                 => Peminjaman::where('status', 'disetujui')->count(),
            'dipinjam'                  => Peminjaman::where('status', 'dipinjam')->count(),
            'pengajuan_pengembalian'    => Peminjaman::where('status', 'pengajuan_pengembalian')->count(),
            'di_denda'                  => Peminjaman::where('status', 'di_denda')->count(),
            'dikembalikan'              => Peminjaman::where('status', 'dikembalikan')->count(),
            'ditolak'                   => Peminjaman::where('status', 'ditolak')->count(),
            'menunggu_verifikasi_bayar' => Peminjaman::where('status_pembayaran_denda', 'menunggu_verifikasi')->count(),
        ];

        return view('petugas.peminjaman.index', compact('peminjaman', 'stats'));
    }

    // ── SHOW ──────────────────────────────────────────────
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'items.alat']);
        return view('petugas.peminjaman.show', compact('peminjaman'));
    }

    // ── APPROVE ───────────────────────────────────────────
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya peminjaman yang menunggu yang bisa disetujui.');
        }
        $peminjaman->update(['status' => 'disetujui']);
        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    // ── REJECT ────────────────────────────────────────────
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya peminjaman yang menunggu yang bisa ditolak.');
        }
        $peminjaman->update([
            'status'           => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);
        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    // ── SERAHKAN ALAT ─────────────────────────────────────
    public function serahkan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang bisa diserahkan.');
        }

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'status'         => 'dipinjam',
                'tanggal_pinjam' => now()->toDateString(),
            ]);

            // Kurangi stok semua item
            foreach ($peminjaman->items as $item) {
                if ($item->alat) {
                    $item->alat->decrement('jumlah', $item->jumlah);
                    if ($item->alat->fresh()->jumlah <= 0) {
                        $item->alat->update(['status' => 'tidak tersedia']);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Alat berhasil diserahkan kepada peminjam.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── TERIMA KEMBALI (normal, tanpa denda) ──────────────
    public function terimaKembali(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pengajuan_pengembalian') {
            return back()->with('error', 'Status tidak sesuai.');
        }

        $tanggalRencana = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
        $tanggalActual  = Carbon::parse($peminjaman->tanggal_kembali_actual)->startOfDay();
        $hariTerlambat  = $tanggalActual->gt($tanggalRencana) ? $tanggalRencana->diffInDays($tanggalActual) : 0;
        $adaDenda       = $hariTerlambat > 0 || $peminjaman->denda > 0;

        if ($adaDenda && $peminjaman->status_pembayaran_denda !== 'terverifikasi') {
            return back()->with('error', 'Denda belum terverifikasi. Verifikasi pembayaran dulu atau gunakan tombol "Kembalikan (Status: Di Denda)".');
        }

        $request->validate([
            'kondisi_alat'    => 'required|in:baik,rusak,hilang',
            'catatan_petugas' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'status'          => 'dikembalikan',
                'kondisi_alat'    => $request->kondisi_alat,
                'catatan_petugas' => $request->catatan_petugas,
            ]);

            // Kembalikan stok semua item
            $this->kembalikanStokItems($peminjaman->items, $request->kondisi_alat);

            DB::commit();
            return back()->with('success', 'Pengembalian berhasil diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── KEMBALIKAN DENDA (dari pengajuan_pengembalian) ────
    public function kembalikanDenda(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pengajuan_pengembalian') {
            return back()->with('error', 'Status tidak sesuai.');
        }

        $request->validate([
            'kondisi_alat'    => 'required|in:baik,rusak,hilang',
            'catatan_petugas' => 'nullable|string|max:500',
        ]);

        $tanggalRencana = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
        $tanggalActual  = $peminjaman->tanggal_kembali_actual
            ? Carbon::parse($peminjaman->tanggal_kembali_actual)->startOfDay()
            : Carbon::now()->startOfDay();

        $hariTerlambat = $tanggalActual->gt($tanggalRencana) ? $tanggalRencana->diffInDays($tanggalActual) : 0;
        $dendaHitung   = $hariTerlambat * 5000;

        if ($hariTerlambat <= 0) {
            return back()->with('error', 'Tidak ada keterlambatan. Gunakan tombol "Terima Pengembalian".');
        }

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'status'                  => 'di_denda',
                'kondisi_alat'            => $request->kondisi_alat,
                'catatan_petugas'         => $request->catatan_petugas,
                'denda'                   => $dendaHitung,
                'jumlah_hari_terlambat'   => $hariTerlambat,
                'status_pembayaran_denda' => 'belum_bayar',
                'tanggal_kembali_actual'  => $tanggalActual->toDateString(),
            ]);

            $this->kembalikanStokItems($peminjaman->items, $request->kondisi_alat);

            DB::commit();
            return back()->with('success', 'Alat dikembalikan ke stok. Status "Di Denda". Denda: Rp ' . number_format($dendaHitung, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── KEMBALIKAN DENDA PAKSA (dari dipinjam) ────────────
    public function kembalikanDendaPaksa(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Status tidak sesuai.');
        }

        $request->validate([
            'kondisi_alat'           => 'required|in:baik,rusak,hilang',
            'tanggal_kembali_actual' => 'required|date',
            'catatan_petugas'        => 'nullable|string|max:500',
        ]);

        $tanggalRencana = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
        $tanggalActual  = Carbon::parse($request->tanggal_kembali_actual)->startOfDay();
        $hariTerlambat  = $tanggalActual->gt($tanggalRencana) ? $tanggalRencana->diffInDays($tanggalActual) : 0;
        $denda          = $hariTerlambat * 5000;

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'status'                  => 'di_denda',
                'tanggal_kembali_actual'  => $request->tanggal_kembali_actual,
                'kondisi_alat'            => $request->kondisi_alat,
                'catatan_petugas'         => $request->catatan_petugas,
                'jumlah_hari_terlambat'   => $hariTerlambat,
                'denda'                   => $denda,
                'status_pembayaran_denda' => 'belum_bayar',
            ]);

            $this->kembalikanStokItems($peminjaman->items, $request->kondisi_alat);

            DB::commit();
            return back()->with('success', 'Alat diproses. Status "Di Denda". Denda: Rp ' . number_format($denda, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── SELESAIKAN DENDA ──────────────────────────────────
    public function selesaikanDenda(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'di_denda') {
            return back()->with('error', 'Hanya peminjaman "Di Denda" yang bisa diselesaikan.');
        }
        if ($peminjaman->status_pembayaran_denda !== 'terverifikasi') {
            return back()->with('error', 'Pembayaran denda belum terverifikasi.');
        }
        $peminjaman->update(['status' => 'dikembalikan']);
        return back()->with('success', 'Peminjaman selesai. Status: "Dikembalikan".');
    }

    // ── VERIFIKASI PEMBAYARAN ─────────────────────────────
    public function verifikasiPembayaran(Request $request, Peminjaman $peminjaman)
    {
        if (!$peminjaman->bukti_pembayaran_denda) {
            return back()->with('error', 'Belum ada bukti pembayaran.');
        }
        if ($peminjaman->status_pembayaran_denda === 'terverifikasi') {
            return back()->with('error', 'Pembayaran sudah terverifikasi sebelumnya.');
        }
        $peminjaman->update(['status_pembayaran_denda' => 'terverifikasi']);
        return back()->with('success', 'Pembayaran denda berhasil diverifikasi.');
    }

    // ── TOLAK PEMBAYARAN ──────────────────────────────────
    public function tolakPembayaran(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_petugas' => 'required|string|max:500']);
        $peminjaman->update([
            'status_pembayaran_denda'  => 'belum_bayar',
            'catatan_admin_pembayaran' => $request->catatan_petugas,
        ]);
        return back()->with('error', 'Pembayaran ditolak. User harus upload ulang bukti pembayaran.');
    }

    // ── PRIVATE HELPER ────────────────────────────────────

    /**
     * Kembalikan stok semua item ke gudang
     */
    private function kembalikanStokItems($items, string $kondisiAlat): void
    {
        foreach ($items as $item) {
            if ($kondisiAlat !== 'hilang' && $item->alat) {
                $item->alat->increment('jumlah', $item->jumlah);
                $item->alat->update([
                    'status' => $kondisiAlat === 'baik' ? 'tersedia' : 'rusak',
                ]);
            }
        }
    }
}