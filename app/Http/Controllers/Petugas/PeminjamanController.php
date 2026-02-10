<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // FIXED: Hapus .kategori karena kategori adalah kolom string, bukan relasi
        $query = Peminjaman::with(['user', 'alat'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter pembayaran denda
        if ($request->has('pembayaran') && $request->pembayaran != '') {
            if ($request->pembayaran === 'menunggu_verifikasi') {
                $query->where('status_pembayaran_denda', 'menunggu_verifikasi');
            } elseif ($request->pembayaran === 'terverifikasi') {
                $query->where('status_pembayaran_denda', 'terverifikasi');
            } elseif ($request->pembayaran === 'ada_denda') {
                $query->where('denda', '>', 0);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('alat', function($alatQuery) use ($search) {
                    $alatQuery->where('nama', 'like', "%{$search}%")
                              ->orWhere('kategori', 'like', "%{$search}%");
                });
            });
        }

        $peminjaman = $query->paginate(15);

        $stats = [
            'total' => Peminjaman::count(),
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'disetujui' => Peminjaman::where('status', 'disetujui')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'pengajuan_pengembalian' => Peminjaman::where('status', 'pengajuan_pengembalian')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'ditolak' => Peminjaman::where('status', 'ditolak')->count(),
            // Stats denda
            'total_denda' => Peminjaman::where('denda', '>', 0)->count(),
            'menunggu_verifikasi_bayar' => Peminjaman::where('status_pembayaran_denda', 'menunggu_verifikasi')->count(),
        ];

        return view('petugas.peminjaman.index', compact('peminjaman', 'stats'));
    }

    public function show(Peminjaman $peminjaman)
    {
        // FIXED: Hapus .kategori karena kategori adalah kolom string, bukan relasi
        $peminjaman->load(['user', 'alat']);
        
        return view('petugas.peminjaman.show', compact('peminjaman'));
    }

    /**
     * PETUGAS: Approve peminjaman
     */
    public function approve(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['menunggu', 'pending'])) {
            return back()->with('error', 'Hanya peminjaman yang menunggu yang bisa disetujui.');
        }

        $peminjaman->update(['status' => Peminjaman::STATUS_DISETUJUI]);

        return back()->with('success', 'Peminjaman berhasil disetujui. Silakan serahkan alat kepada peminjam.');
    }

    /**
     * PETUGAS: Reject peminjaman
     */
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['menunggu', 'pending'])) {
            return back()->with('error', 'Hanya peminjaman yang menunggu yang bisa ditolak.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DITOLAK,
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * PETUGAS: Serahkan alat (status: disetujui → dipinjam)
     */
    public function serahkan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DISETUJUI) {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang bisa diserahkan.');
        }

        DB::beginTransaction();
        try {
            // Update status menjadi "dipinjam"
            $peminjaman->update([
                'status' => Peminjaman::STATUS_DIPINJAM,
                'tanggal_pinjam' => now()
            ]);

            // Kurangi stok alat
            $alat = $peminjaman->alat;
            if ($alat) {
                $alat->decrement('jumlah', $peminjaman->jumlah);

                if ($alat->jumlah <= 0) {
                    $alat->update(['status' => 'tidak tersedia']);
                }
            }

            DB::commit();
            return back()->with('success', 'Alat berhasil diserahkan. Status berubah menjadi "Dipinjam". Stok alat telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * PETUGAS: Verifikasi pembayaran denda
     */
    public function verifikasiPembayaran(Request $request, Peminjaman $peminjaman)
    {
        // Validasi ada denda dan bukti pembayaran
        if ($peminjaman->denda <= 0) {
            return back()->with('error', 'Tidak ada denda pada peminjaman ini.');
        }

        if (!$peminjaman->bukti_pembayaran_denda) {
            return back()->with('error', 'Belum ada bukti pembayaran yang diupload.');
        }

        if ($peminjaman->status_pembayaran_denda === 'terverifikasi') {
            return back()->with('error', 'Pembayaran sudah terverifikasi sebelumnya.');
        }

        // Update status pembayaran
        $peminjaman->update([
            'status_pembayaran_denda' => 'terverifikasi',
            'catatan_petugas' => ($peminjaman->catatan_petugas ?? '') . "\n\n[Pembayaran Denda] " . ($request->catatan_petugas ?? 'Pembayaran denda telah diverifikasi dan diterima.')
        ]);

        return back()->with('success', 'Pembayaran denda berhasil diverifikasi. User dapat mengembalikan alat.');
    }

    /**
     * PETUGAS: Tolak pembayaran denda
     */
    public function tolakPembayaran(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'catatan_petugas' => 'required|string|max:500'
        ], [
            'catatan_petugas.required' => 'Alasan penolakan harus diisi'
        ]);

        // Validasi
        if ($peminjaman->denda <= 0) {
            return back()->with('error', 'Tidak ada denda pada peminjaman ini.');
        }

        if (!$peminjaman->bukti_pembayaran_denda) {
            return back()->with('error', 'Belum ada bukti pembayaran.');
        }

        // Update status kembali ke belum bayar dengan catatan
        $peminjaman->update([
            'status_pembayaran_denda' => 'belum_bayar',
            'catatan_admin_pembayaran' => $request->catatan_petugas
        ]);

        return back()->with('error', 'Pembayaran ditolak. User harus upload ulang bukti pembayaran yang benar.');
    }

    /**
     * PETUGAS: Terima pengembalian (status: pengajuan_pengembalian → dikembalikan)
     */
    public function terimaKembali(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_PENGAJUAN_PENGEMBALIAN) {
            return back()->with('error', 'Hanya peminjaman dengan pengajuan pengembalian yang bisa diproses.');
        }

        // VALIDASI: Jika ada denda, harus sudah terverifikasi
        if ($peminjaman->denda > 0 && $peminjaman->status_pembayaran_denda !== 'terverifikasi') {
            $statusText = match($peminjaman->status_pembayaran_denda) {
                'belum_bayar' => 'belum dibayar',
                'menunggu_verifikasi' => 'menunggu verifikasi',
                default => 'belum terverifikasi'
            };
            
            return back()->with('error', "Pengembalian tidak dapat diproses. User memiliki denda Rp " . number_format($peminjaman->denda, 0, ',', '.') . " yang {$statusText}. Verifikasi pembayaran terlebih dahulu.");
        }

        // Validasi kondisi alat
        $request->validate([
            'kondisi_alat' => 'required|in:baik,rusak,hilang',
            'catatan_petugas' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Update status menjadi "dikembalikan"
            $updateData = [
                'status' => Peminjaman::STATUS_DIKEMBALIKAN,
                'kondisi_alat' => $request->kondisi_alat,
                'catatan_petugas' => $request->catatan_petugas
            ];

            // Jika tanggal_kembali_actual belum diset (untuk backward compatibility)
            if (!$peminjaman->tanggal_kembali_actual) {
                $updateData['tanggal_kembali_actual'] = now();
            }

            $peminjaman->update($updateData);

            // Kembalikan stok alat (jika tidak hilang dan alat masih ada)
            if ($request->kondisi_alat !== 'hilang' && $peminjaman->alat) {
                $alat = $peminjaman->alat;
                $alat->increment('jumlah', $peminjaman->jumlah);

                // Update status alat
                if ($request->kondisi_alat === 'baik') {
                    $alat->update(['status' => 'tersedia']);
                } elseif ($request->kondisi_alat === 'rusak') {
                    $alat->update(['status' => 'rusak']);
                }
            }

            DB::commit();
            return back()->with('success', 'Pengembalian berhasil diproses. Stok alat telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}