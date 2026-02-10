<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // middleware applied via routes; remove to avoid constructor errors
    }

    public function index(Request $request)
    {
        $totalUsers = User::count();
        
        // Data untuk dashboard
        $hariIni = now()->toDateString();
        
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', $hariIni)->count();
        $pengembalianHariIni = Peminjaman::whereDate('tanggal_kembali_actual', $hariIni)->count();
        $menungguPengembalian = Peminjaman::where('status', 'dipinjam')->count();
        $alatTersedia = Alat::where('status', 'tersedia')->sum('jumlah');
        
        return view('petugas.dashboard', compact(
            'totalUsers',
            'peminjamanHariIni',
            'pengembalianHariIni',
            'menungguPengembalian',
            'alatTersedia'
        ));
    }
}