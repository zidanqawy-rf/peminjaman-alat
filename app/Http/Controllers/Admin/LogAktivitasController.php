<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $status = $request->get('status');
        $tanggal_dari = $request->get('tanggal_dari');
        $tanggal_sampai = $request->get('tanggal_sampai');
        $search = $request->get('search');
        
        // Build query
        $query = Peminjaman::with(['user', 'alat'])
            ->latest('updated_at');
        
        // Apply filters
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
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('kelas', 'like', "%{$search}%");
                })
                ->orWhereHas('alat', function($q) use ($search) {
                    $q->where('nama_alat', 'like', "%{$search}%");
                });
            });
        }
        
        // Paginate results
        $logAktivitas = $query->paginate(20)->withQueryString();
        
        // Transform data
        $logAktivitas->getCollection()->transform(function ($peminjaman) {
            return [
                'id' => $peminjaman->id,
                'user_name' => $peminjaman->user->name ?? 'Unknown',
                'user_kelas' => $peminjaman->user->kelas ?? '-',
                'alat_name' => $peminjaman->alat->nama_alat ?? 'Unknown',
                'jumlah' => $peminjaman->jumlah ?? 1,
                'status' => $peminjaman->status,
                'status_text' => $this->getStatusText($peminjaman->status),
                'status_color' => $this->getStatusColor($peminjaman->status),
                'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                'tanggal_kembali' => $peminjaman->tanggal_kembali,
                'keperluan' => $peminjaman->keperluan,
                'updated_at' => $peminjaman->updated_at,
                'time_ago' => $peminjaman->updated_at->diffForHumans(),
            ];
        });
        
        return view('admin.log-aktivitas.index', compact('logAktivitas'));
    }
    
    /**
     * Get status text label
     */
    private function getStatusText($status)
    {
        $statusLabels = [
            Peminjaman::STATUS_MENUNGGU => 'Menunggu Persetujuan',
            Peminjaman::STATUS_PENDING => 'Pending',
            Peminjaman::STATUS_DISETUJUI => 'Disetujui',
            Peminjaman::STATUS_DIPINJAM => 'Sedang Dipinjam',
            Peminjaman::STATUS_PENGAJUAN_PENGEMBALIAN => 'Pengajuan Pengembalian',
            Peminjaman::STATUS_DIKEMBALIKAN => 'Dikembalikan',
            Peminjaman::STATUS_DITOLAK => 'Ditolak',
            Peminjaman::STATUS_SELESAI => 'Selesai',
        ];
        
        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
    
    /**
     * Get status color for badge
     */
    private function getStatusColor($status)
    {
        $statusColors = [
            Peminjaman::STATUS_MENUNGGU => 'bg-yellow-100 text-yellow-800',
            Peminjaman::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            Peminjaman::STATUS_DISETUJUI => 'bg-blue-100 text-blue-800',
            Peminjaman::STATUS_DIPINJAM => 'bg-purple-100 text-purple-800',
            Peminjaman::STATUS_PENGAJUAN_PENGEMBALIAN => 'bg-indigo-100 text-indigo-800',
            Peminjaman::STATUS_DIKEMBALIKAN => 'bg-green-100 text-green-800',
            Peminjaman::STATUS_DITOLAK => 'bg-red-100 text-red-800',
            Peminjaman::STATUS_SELESAI => 'bg-green-100 text-green-800',
        ];
        
        return $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
    }
}