<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PengaturanDenda;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'items.alat'])
            ->where('denda', '>', 0)
            ->latest();

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran_denda', $request->status_pembayaran);
        }
        if ($request->filled('status_peminjaman')) {
            $query->where('status', $request->status_peminjaman);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('items.alat', fn($a) => $a->where('nama', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $dendas = $query->paginate(15)->withQueryString();

        $stats = [
            'total_denda'           => Peminjaman::where('denda', '>', 0)->count(),
            'total_nominal'         => Peminjaman::where('denda', '>', 0)->sum('denda'),
            'belum_bayar'           => Peminjaman::where('denda', '>', 0)
                                          ->where(fn($q) => $q->whereNull('status_pembayaran_denda')
                                              ->orWhere('status_pembayaran_denda', 'belum_bayar'))
                                          ->count(),
            'menunggu_verifikasi'   => Peminjaman::where('denda', '>', 0)
                                          ->where('status_pembayaran_denda', 'menunggu_verifikasi')->count(),
            'terverifikasi'         => Peminjaman::where('denda', '>', 0)
                                          ->where('status_pembayaran_denda', 'terverifikasi')->count(),
            'nominal_terverifikasi' => Peminjaman::where('denda', '>', 0)
                                          ->where('status_pembayaran_denda', 'terverifikasi')->sum('denda'),
            'nominal_belum_lunas'   => Peminjaman::where('denda', '>', 0)
                                          ->where(fn($q) => $q->whereNull('status_pembayaran_denda')
                                              ->orWhere('status_pembayaran_denda', 'belum_bayar'))
                                          ->sum('denda'),
        ];

        $pengaturan = PengaturanDenda::aktif();

        return view('admin.denda.index', compact('dendas', 'stats', 'pengaturan'));
    }

    // ── SIMPAN PENGATURAN TARIF & INFO PEMBAYARAN ─────────
    public function simpanPengaturan(Request $request)
    {
        $request->validate([
            'tarif_per_hari' => 'required|integer|min:0|max:1000000',
            'keterangan'     => 'nullable|string|max:255',
            'nama_bank'      => 'required|string|max:100',
            'no_rekening'    => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:150',
            'no_dana'        => 'nullable|string|max:20',
            'nama_dana'      => 'nullable|string|max:150',
        ], [
            'tarif_per_hari.required' => 'Tarif denda harus diisi.',
            'tarif_per_hari.integer'  => 'Tarif denda harus berupa angka.',
            'tarif_per_hari.min'      => 'Tarif denda minimal Rp 0.',
            'tarif_per_hari.max'      => 'Tarif denda maksimal Rp 1.000.000.',
            'nama_bank.required'      => 'Nama bank harus diisi.',
            'no_rekening.required'    => 'Nomor rekening harus diisi.',
            'atas_nama.required'      => 'Nama pemilik rekening harus diisi.',
        ]);

        PengaturanDenda::aktif()->update([
            'tarif_per_hari' => $request->tarif_per_hari,
            'keterangan'     => $request->keterangan,
            'nama_bank'      => $request->nama_bank,
            'no_rekening'    => $request->no_rekening,
            'atas_nama'      => $request->atas_nama,
            'no_dana'        => $request->no_dana ?: null,
            'nama_dana'      => $request->nama_dana ?: null,
        ]);

        return back()->with('success', 'Pengaturan denda berhasil diperbarui.');
    }

    // ── SHOW ──────────────────────────────────────────────
    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->denda <= 0) {
            return redirect()->route('admin.denda.index')
                ->with('error', 'Peminjaman ini tidak memiliki denda.');
        }
        $peminjaman->load(['user', 'items.alat']);
        $pengaturan = PengaturanDenda::aktif();

        return view('admin.denda.show', compact('peminjaman', 'pengaturan'));
    }

    // ── VERIFIKASI PEMBAYARAN ─────────────────────────────
    public function verifikasiPembayaran(Request $request, Peminjaman $peminjaman)
    {
        if (!$peminjaman->bukti_pembayaran_denda) {
            return back()->with('error', 'Belum ada bukti pembayaran yang diupload.');
        }
        if ($peminjaman->status_pembayaran_denda === 'terverifikasi') {
            return back()->with('error', 'Pembayaran sudah terverifikasi sebelumnya.');
        }

        $peminjaman->update(['status_pembayaran_denda' => 'terverifikasi']);

        if ($peminjaman->status === 'di_denda') {
            $peminjaman->update(['status' => 'dikembalikan']);
        }

        return back()->with('success', 'Pembayaran denda berhasil diverifikasi.');
    }

    // ── TOLAK PEMBAYARAN ──────────────────────────────────
    public function tolakPembayaran(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_admin' => 'required|string|max:500']);

        $peminjaman->update([
            'status_pembayaran_denda'  => 'belum_bayar',
            'catatan_admin_pembayaran' => $request->catatan_admin,
        ]);

        return back()->with('error', 'Pembayaran ditolak. User diminta upload ulang bukti pembayaran.');
    }

    // ── HAPUS DENDA ───────────────────────────────────────
    public function hapusDenda(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['alasan_hapus' => 'required|string|max:500']);

        $peminjaman->update([
            'denda'                    => 0,
            'jumlah_hari_terlambat'    => 0,
            'status_pembayaran_denda'  => null,
            'bukti_pembayaran_denda'   => null,
            'catatan_admin_pembayaran' => 'Denda dihapus oleh admin: ' . $request->alasan_hapus,
        ]);

        return back()->with('success', 'Denda berhasil dihapus/direset.');
    }

    // ── UBAH NOMINAL DENDA ────────────────────────────────
    public function ubahDenda(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'denda_baru'  => 'required|integer|min:0',
            'alasan_ubah' => 'required|string|max:500',
        ]);

        $peminjaman->update([
            'denda'                    => $request->denda_baru,
            'catatan_admin_pembayaran' => 'Denda diubah admin: ' . $request->alasan_ubah,
        ]);

        return back()->with('success', 'Nominal denda berhasil diperbarui.');
    }

    // ── EXPORT CSV ────────────────────────────────────────
    public function export(Request $request)
    {
        $dendas = Peminjaman::with(['user', 'items.alat'])
            ->where('denda', '>', 0)
            ->when($request->filled('status_pembayaran'), fn($q) => $q->where('status_pembayaran_denda', $request->status_pembayaran))
            ->latest()
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="master-denda-' . date('Ymd') . '.csv"',
        ];

        $columns = [
            'ID Peminjaman', 'Nama Peminjam', 'Email', 'Alat',
            'Tgl Rencana Kembali', 'Tgl Kembali Aktual',
            'Hari Terlambat', 'Nominal Denda', 'Status Pembayaran', 'Status Peminjaman',
        ];

        $callback = function () use ($dendas, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($dendas as $d) {
                fputcsv($file, [
                    $d->id,
                    optional($d->user)->name,
                    optional($d->user)->email,
                    $d->nama_alat_singkat,
                    $d->tanggal_kembali?->format('d/m/Y'),
                    $d->tanggal_kembali_actual?->format('d/m/Y'),
                    $d->jumlah_hari_terlambat,
                    $d->denda,
                    $d->status_pembayaran_denda ?? 'belum_bayar',
                    $d->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}