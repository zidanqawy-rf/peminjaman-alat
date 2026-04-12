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
    // ── HELPER: build query berdasarkan filter ────────────
    private function buildQuery(Request $request): array
    {
        $filterType  = $request->get('filter_type', 'bulan');
        $filterDate  = $request->get('filter_date', now()->format('Y-m-d'));
        $filterMonth = $request->get('filter_month', now()->format('Y-m'));
        $filterYear  = $request->get('filter_year', now()->format('Y'));

        $query = Peminjaman::with(['user', 'items.alat']);

        switch ($filterType) {
            case 'hari':
                $date = Carbon::parse($filterDate);
                $query->whereDate('created_at', $date);
                $periodTitle = $date->format('d F Y');
                $filename    = 'Laporan_' . $date->format('d-m-Y');
                break;

            case 'tahun':
                $date = Carbon::parse($filterYear . '-01-01');
                $query->whereYear('created_at', $date->year);
                $periodTitle = $date->format('Y');
                $filename    = 'Laporan_' . $date->format('Y');
                break;

            case 'bulan':
            default:
                $date = Carbon::parse($filterMonth . '-01');
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                $periodTitle = $date->format('F Y');
                $filename    = 'Laporan_' . $date->format('m-Y');
                $filterType  = 'bulan';
                break;
        }

        return compact('query', 'filterType', 'filterDate', 'filterMonth', 'filterYear', 'periodTitle', 'filename');
    }

    // ── HELPER: hitung stats dari koleksi ────────────────
    private function buildStats($peminjaman): array
    {
        return [
            'total_peminjaman'       => $peminjaman->count(),
            'menunggu'               => $peminjaman->where('status', 'menunggu')->count(),
            'disetujui'              => $peminjaman->where('status', 'disetujui')->count(),
            'dipinjam'               => $peminjaman->where('status', 'dipinjam')->count(),
            'pengajuan_pengembalian' => $peminjaman->where('status', 'pengajuan_pengembalian')->count(),
            'dikembalikan'           => $peminjaman->where('status', 'dikembalikan')->count(),
            'ditolak'                => $peminjaman->where('status', 'ditolak')->count(),
            'total_denda'            => $peminjaman->sum('denda'),
            'denda_terverifikasi'    => $peminjaman->where('status_pembayaran_denda', 'terverifikasi')->sum('denda'),
            'denda_menunggu'         => $peminjaman->where('status_pembayaran_denda', 'menunggu_verifikasi')->sum('denda'),
        ];
    }

    // ── INDEX ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $filter      = $this->buildQuery($request);

        // Unpack semua variabel dari $filter
        $filterType  = $filter['filterType'];
        $filterDate  = $filter['filterDate'];
        $filterMonth = $filter['filterMonth'];
        $filterYear  = $filter['filterYear'];
        $periodTitle = $filter['periodTitle'];

        $peminjaman  = $filter['query']->orderBy('created_at', 'desc')->get();
        $stats       = $this->buildStats($peminjaman);

        $alatStats = $peminjaman->flatMap(fn($p) => $p->items)
            ->groupBy('alat_id')
            ->map(function ($items) {
                $alat = optional($items->first())->alat;

                return [
                    'alat'         => $alat,
                    'total'        => $items->count(),
                    'dipinjam'     => $items->filter(fn($i) => optional($i->peminjaman)->status === 'dipinjam')->count(),
                    'dikembalikan' => $items->filter(fn($i) => optional($i->peminjaman)->status === 'dikembalikan')->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(10);

        $userStats = $peminjaman->groupBy('user_id')->map(function ($items) {
            return [
                'user'  => $items->first()->user,
                'total' => $items->count(),
                'denda' => $items->sum('denda'),
            ];
        })->sortByDesc('total')->take(10);

        return view('petugas.laporan.index', compact(
            'peminjaman', 'stats', 'alatStats', 'userStats',
            'filterType', 'filterDate', 'filterMonth', 'filterYear', 'periodTitle'
        ));
    }

    // ── PRINT ─────────────────────────────────────────────
    public function print(Request $request)
    {
        $filter      = $this->buildQuery($request);
        $peminjaman  = $filter['query']->orderBy('created_at', 'desc')->get();
        $stats       = $this->buildStats($peminjaman);
        $periodTitle = $filter['periodTitle'];

        return view('petugas.laporan.print', compact('peminjaman', 'stats', 'periodTitle'));
    }

    // ── DOWNLOAD PDF ──────────────────────────────────────
    public function downloadPdf(Request $request)
    {
        $filter      = $this->buildQuery($request);
        $peminjaman  = $filter['query']->orderBy('created_at', 'desc')->get();
        $stats       = $this->buildStats($peminjaman);
        $periodTitle = $filter['periodTitle'];
        $filename    = $filter['filename'];

        $pdf = Pdf::loadView('petugas.laporan.pdf', compact('peminjaman', 'stats', 'periodTitle'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ]);

        return $pdf->download($filename . '.pdf');
    }

    // ── STREAM PDF ────────────────────────────────────────
    public function streamPdf(Request $request)
    {
        $filter      = $this->buildQuery($request);
        $peminjaman  = $filter['query']->orderBy('created_at', 'desc')->get();
        $stats       = $this->buildStats($peminjaman);
        $periodTitle = $filter['periodTitle'];

        $pdf = Pdf::loadView('petugas.laporan.pdf', compact('peminjaman', 'stats', 'periodTitle'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ]);

        return $pdf->stream('laporan-peminjaman.pdf');
    }

    // ── EXPORT (placeholder) ──────────────────────────────
    public function export(Request $request)
    {
        return back()->with('info', 'Fitur export dalam pengembangan');
    }
}