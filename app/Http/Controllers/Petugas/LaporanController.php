<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display laporan page
     */
    public function index(Request $request)
    {
        // Ambil parameter filter
        $filterType = $request->get('filter_type', 'bulan'); // hari, bulan, tahun
        $filterDate = $request->get('filter_date', now()->format('Y-m-d'));
        $filterMonth = $request->get('filter_month', now()->format('Y-m'));
        $filterYear = $request->get('filter_year', now()->format('Y'));

        // Query peminjaman berdasarkan filter
        $query = Peminjaman::with(['user', 'alat']);

        switch ($filterType) {
            case 'hari':
                $date = Carbon::parse($filterDate);
                $query->whereDate('created_at', $date);
                $periodTitle = $date->format('d F Y');
                break;

            case 'bulan':
                $date = Carbon::parse($filterMonth . '-01');
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                break;

            case 'tahun':
                $date = Carbon::parse($filterYear . '-01-01');
                $query->whereYear('created_at', $date->year);
                $periodTitle = $date->format('Y');
                break;

            default:
                $date = Carbon::now();
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                break;
        }

        // Ambil data peminjaman
        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $stats = [
            'total_peminjaman' => $peminjaman->count(),
            'menunggu' => $peminjaman->where('status', 'menunggu')->count(),
            'disetujui' => $peminjaman->where('status', 'disetujui')->count(),
            'dipinjam' => $peminjaman->where('status', 'dipinjam')->count(),
            'pengajuan_pengembalian' => $peminjaman->where('status', 'pengajuan_pengembalian')->count(),
            'dikembalikan' => $peminjaman->where('status', 'dikembalikan')->count(),
            'ditolak' => $peminjaman->where('status', 'ditolak')->count(),
            'total_denda' => $peminjaman->sum('denda'),
            'denda_terverifikasi' => $peminjaman->where('status_pembayaran_denda', 'terverifikasi')->sum('denda'),
            'denda_menunggu' => $peminjaman->where('status_pembayaran_denda', 'menunggu_verifikasi')->sum('denda'),
        ];

        // Statistik per alat
        $alatStats = $peminjaman->groupBy('tool_id')->map(function ($items) {
            return [
                'alat' => $items->first()->alat,
                'total' => $items->count(),
                'dipinjam' => $items->where('status', 'dipinjam')->count(),
                'dikembalikan' => $items->where('status', 'dikembalikan')->count(),
            ];
        })->sortByDesc('total')->take(10);

        // Statistik per user (peminjam terbanyak)
        $userStats = $peminjaman->groupBy('user_id')->map(function ($items) {
            return [
                'user' => $items->first()->user,
                'total' => $items->count(),
                'denda' => $items->sum('denda'),
            ];
        })->sortByDesc('total')->take(10);

        return view('petugas.laporan.index', compact(
            'peminjaman',
            'stats',
            'alatStats',
            'userStats',
            'filterType',
            'filterDate',
            'filterMonth',
            'filterYear',
            'periodTitle'
        ));
    }

    /**
     * Export laporan to Excel/PDF
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel'); // excel atau pdf
        
        // Implementasi export sesuai kebutuhan
        // Bisa menggunakan library seperti Laravel Excel atau DomPDF
        
        return back()->with('info', 'Fitur export dalam pengembangan');
    }

    /**
     * Print laporan
     */
    public function print(Request $request)
    {
        // Ambil parameter filter yang sama dengan index
        $filterType = $request->get('filter_type', 'bulan');
        $filterDate = $request->get('filter_date', now()->format('Y-m-d'));
        $filterMonth = $request->get('filter_month', now()->format('Y-m'));
        $filterYear = $request->get('filter_year', now()->format('Y'));

        // Query peminjaman (sama dengan index)
        $query = Peminjaman::with(['user', 'alat']);

        switch ($filterType) {
            case 'hari':
                $date = Carbon::parse($filterDate);
                $query->whereDate('created_at', $date);
                $periodTitle = $date->format('d F Y');
                break;

            case 'bulan':
                $date = Carbon::parse($filterMonth . '-01');
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                break;

            case 'tahun':
                $date = Carbon::parse($filterYear . '-01-01');
                $query->whereYear('created_at', $date->year);
                $periodTitle = $date->format('Y');
                break;

            default:
                $date = Carbon::now();
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                break;
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $stats = [
            'total_peminjaman' => $peminjaman->count(),
            'menunggu' => $peminjaman->where('status', 'menunggu')->count(),
            'disetujui' => $peminjaman->where('status', 'disetujui')->count(),
            'dipinjam' => $peminjaman->where('status', 'dipinjam')->count(),
            'pengajuan_pengembalian' => $peminjaman->where('status', 'pengajuan_pengembalian')->count(),
            'dikembalikan' => $peminjaman->where('status', 'dikembalikan')->count(),
            'ditolak' => $peminjaman->where('status', 'ditolak')->count(),
            'total_denda' => $peminjaman->sum('denda'),
        ];

        return view('petugas.laporan.print', compact('peminjaman', 'stats', 'periodTitle'));
    }

    /**
     * Download laporan as PDF
     */
    public function downloadPdf(Request $request)
    {
        // Ambil parameter filter yang sama dengan index
        $filterType = $request->get('filter_type', 'bulan');
        $filterDate = $request->get('filter_date', now()->format('Y-m-d'));
        $filterMonth = $request->get('filter_month', now()->format('Y-m'));
        $filterYear = $request->get('filter_year', now()->format('Y'));

        // Query peminjaman (sama dengan index)
        $query = Peminjaman::with(['user', 'alat']);

        switch ($filterType) {
            case 'hari':
                $date = Carbon::parse($filterDate);
                $query->whereDate('created_at', $date);
                $periodTitle = $date->format('d F Y');
                $filename = 'Laporan_' . $date->format('d-m-Y');
                break;

            case 'bulan':
                $date = Carbon::parse($filterMonth . '-01');
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                $filename = 'Laporan_' . $date->format('m-Y');
                break;

            case 'tahun':
                $date = Carbon::parse($filterYear . '-01-01');
                $query->whereYear('created_at', $date->year);
                $periodTitle = $date->format('Y');
                $filename = 'Laporan_' . $date->format('Y');
                break;

            default:
                $date = Carbon::now();
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                $filename = 'Laporan_' . $date->format('m-Y');
                break;
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $stats = [
            'total_peminjaman' => $peminjaman->count(),
            'menunggu' => $peminjaman->where('status', 'menunggu')->count(),
            'disetujui' => $peminjaman->where('status', 'disetujui')->count(),
            'dipinjam' => $peminjaman->where('status', 'dipinjam')->count(),
            'pengajuan_pengembalian' => $peminjaman->where('status', 'pengajuan_pengembalian')->count(),
            'dikembalikan' => $peminjaman->where('status', 'dikembalikan')->count(),
            'ditolak' => $peminjaman->where('status', 'ditolak')->count(),
            'total_denda' => $peminjaman->sum('denda'),
        ];

        // Load view untuk PDF
        $pdf = Pdf::loadView('petugas.laporan.pdf', compact('peminjaman', 'stats', 'periodTitle'))
            ->setPaper('a4', 'landscape') // Set ukuran dan orientasi kertas
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        // Download PDF
        return $pdf->download($filename . '.pdf');
    }

    /**
     * Stream PDF (view di browser)
     */
    public function streamPdf(Request $request)
    {
        // Ambil parameter filter yang sama dengan index
        $filterType = $request->get('filter_type', 'bulan');
        $filterDate = $request->get('filter_date', now()->format('Y-m-d'));
        $filterMonth = $request->get('filter_month', now()->format('Y-m'));
        $filterYear = $request->get('filter_year', now()->format('Y'));

        // Query peminjaman (sama dengan index)
        $query = Peminjaman::with(['user', 'alat']);

        switch ($filterType) {
            case 'hari':
                $date = Carbon::parse($filterDate);
                $query->whereDate('created_at', $date);
                $periodTitle = $date->format('d F Y');
                break;

            case 'bulan':
                $date = Carbon::parse($filterMonth . '-01');
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                break;

            case 'tahun':
                $date = Carbon::parse($filterYear . '-01-01');
                $query->whereYear('created_at', $date->year);
                $periodTitle = $date->format('Y');
                break;

            default:
                $date = Carbon::now();
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                break;
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $stats = [
            'total_peminjaman' => $peminjaman->count(),
            'menunggu' => $peminjaman->where('status', 'menunggu')->count(),
            'disetujui' => $peminjaman->where('status', 'disetujui')->count(),
            'dipinjam' => $peminjaman->where('status', 'dipinjam')->count(),
            'pengajuan_pengembalian' => $peminjaman->where('status', 'pengajuan_pengembalian')->count(),
            'dikembalikan' => $peminjaman->where('status', 'dikembalikan')->count(),
            'ditolak' => $peminjaman->where('status', 'ditolak')->count(),
            'total_denda' => $peminjaman->sum('denda'),
        ];

        // Load view untuk PDF
        $pdf = Pdf::loadView('petugas.laporan.pdf', compact('peminjaman', 'stats', 'periodTitle'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        // Stream PDF (tampilkan di browser)
        return $pdf->stream('laporan-peminjaman.pdf');
    }
}