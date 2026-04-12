<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();

        $totalPeminjaman = Peminjaman::count();

        $peminjamanAktif = Peminjaman::whereIn('status', [
            'disetujui',
            'dipinjam',
            'pengajuan_pengembalian',
        ])->count();

        $peminjamanSelesai = Peminjaman::whereIn('status', [
            'dikembalikan',
            'selesai',
        ])->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanSelesai'
        ));
    }
}