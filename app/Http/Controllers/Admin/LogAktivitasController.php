<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $status         = $request->get('status');
        $tanggal_dari   = $request->get('tanggal_dari');
        $tanggal_sampai = $request->get('tanggal_sampai');
        $search         = $request->get('search');

        $query = Peminjaman::with(['user', 'items.alat'])
            ->latest('updated_at');

        if ($status) {
            $query->where('status', $status);
        }

        if ($tanggal_dari) {
            $query->whereDate('tanggal_pinjam', '>=', $tanggal_dari);
        }

        if ($tanggal_sampai) {
            $query->whereDate('tanggal_pinjam', '<=', $tanggal_sampai);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('kelas', 'like', "%{$search}%");
                })
                // ✅ FIX: ganti 'nama_alat' → 'nama' sesuai kolom di tabel alats
                ->orWhereHas('items.alat', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $logAktivitas = $query->paginate(20)->withQueryString();

        $logAktivitas->getCollection()->transform(function ($peminjaman) {
            $firstItem  = $peminjaman->items->first();
            // ✅ FIX: ganti ->nama_alat → ->nama sesuai kolom di tabel alats
            $namaAlat   = optional(optional($firstItem)->alat)->nama ?? 'Unknown';
            $jumlahItem = $peminjaman->items->count();

            $alatDisplay = $jumlahItem > 1
                ? $namaAlat . ' (+' . ($jumlahItem - 1) . ' lainnya)'
                : $namaAlat;

            return [
                'id'              => $peminjaman->id,
                'user_name'       => $peminjaman->user->name ?? 'Unknown',
                'user_kelas'      => $peminjaman->user->kelas ?? '-',
                'alat_name'       => $alatDisplay,
                'jumlah'          => $peminjaman->items->sum('jumlah') ?? 1,
                'status'          => $peminjaman->status,
                'status_text'     => $this->getStatusText($peminjaman->status),
                'status_color'    => $this->getStatusColor($peminjaman->status),
                'tanggal_pinjam'  => $peminjaman->tanggal_pinjam,
                'tanggal_kembali' => $peminjaman->tanggal_kembali,
                'keperluan'       => $peminjaman->keperluan,
                'updated_at'      => $peminjaman->updated_at,
                'time_ago'        => $peminjaman->updated_at->diffForHumans(),
            ];
        });

        return view('admin.log-aktivitas.index', compact('logAktivitas'));
    }

    private function getStatusText($status)
    {
        $statusLabels = [
            'menunggu'               => 'Menunggu Persetujuan',
            'disetujui'              => 'Disetujui',
            'dipinjam'               => 'Sedang Dipinjam',
            'pengajuan_pengembalian' => 'Pengajuan Pengembalian',
            'dikembalikan'           => 'Dikembalikan',
            'ditolak'                => 'Ditolak',
            'selesai'                => 'Selesai',
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    private function getStatusColor($status)
    {
        $statusColors = [
            'menunggu'               => 'bg-yellow-100 text-yellow-800',
            'disetujui'              => 'bg-blue-100 text-blue-800',
            'dipinjam'               => 'bg-purple-100 text-purple-800',
            'pengajuan_pengembalian' => 'bg-indigo-100 text-indigo-800',
            'dikembalikan'           => 'bg-green-100 text-green-800',
            'ditolak'                => 'bg-red-100 text-red-800',
            'selesai'                => 'bg-green-100 text-green-800',
        ];

        return $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
    }
}