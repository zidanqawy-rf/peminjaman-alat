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
        $query = Peminjaman::with(['user', 'alat'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('alat', function($alatQuery) use ($search) {
                    $alatQuery->where('nama', 'like', "%{$search}%")
                              ->orWhere('nama_alat', 'like', "%{$search}%");
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
        ];

        return view('petugas.peminjaman.index', compact('peminjaman', 'stats'));
    }

    public function show(Peminjaman $peminjaman)
    {
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
            $alat->decrement('jumlah', $peminjaman->jumlah);

            if ($alat->jumlah <= 0) {
                $alat->update(['status' => 'tidak tersedia']);
            }

            DB::commit();
            return back()->with('success', 'Alat berhasil diserahkan. Status berubah menjadi "Dipinjam". Stok alat telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * PETUGAS: Terima pengembalian (status: pengajuan_pengembalian → dikembalikan)
     */
    public function terimaKembali(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== Peminjaman::STATUS_PENGAJUAN_PENGEMBALIAN) {
            return back()->with('error', 'Hanya peminjaman dengan pengajuan pengembalian yang bisa diproses.');
        }

        // Validasi kondisi alat
        $request->validate([
            'kondisi_alat' => 'required|in:baik,rusak,hilang',
            'catatan_petugas' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Update status menjadi "dikembalikan"
            $peminjaman->update([
                'status' => Peminjaman::STATUS_DIKEMBALIKAN,
                'tanggal_kembali_actual' => now(),
                'kondisi_alat' => $request->kondisi_alat,
                'catatan_petugas' => $request->catatan_petugas
            ]);

            // Kembalikan stok alat (jika tidak hilang)
            if ($request->kondisi_alat !== 'hilang') {
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