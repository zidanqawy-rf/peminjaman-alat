<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - {{ $periodTitle }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            page-break-inside: avoid;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            color: #888;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .stat-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .stat-card h3 {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .stat-card p {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background: #333;
            color: white;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            font-size: 11px;
            text-transform: uppercase;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-purple { background: #e9d5ff; color: #6b21a8; }
        .badge-gray { background: #e5e7eb; color: #374151; }
        .badge-red { background: #fee2e2; color: #991b1b; }

        .footer {
            margin-top: 50px;
            text-align: right;
            page-break-inside: avoid;
        }

        .signature {
            margin-top: 80px;
        }

        @media print {
            body {
                padding: 10px;
                margin: 0;
            }
            
            .no-print {
                display: none !important;
            }

            /* Pastikan header di halaman pertama */
            .header {
                margin-top: 0;
                padding-top: 10px;
            }

            /* Hindari page break di dalam elemen */
            .stats, .header, .footer {
                page-break-inside: avoid;
            }

            /* Margin untuk print */
            @page {
                margin: 1cm;
            }
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
            page-break-after: avoid;
        }

        /* Button Styles */
        .btn-container {
            text-align: right;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-purple {
            background: #8b5cf6;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn svg {
            width: 16px;
            height: 16px;
        }

        /* Container untuk konten yang akan di-print */
        .print-content {
            background: white;
        }
    </style>
</head>
<body>
    <!-- Action Buttons - Tidak akan di-print -->
    <div class="no-print btn-container">
        <!-- Tombol Cetak -->
        <button onclick="window.print()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Laporan
        </button>

        <!-- Tombol Download PDF -->
        <a href="{{ route('petugas.laporan.pdf.download', request()->query()) }}" class="btn btn-danger">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Download PDF
        </a>

        <!-- Tombol Lihat PDF -->
        <a href="{{ route('petugas.laporan.pdf.stream', request()->query()) }}" target="_blank" class="btn btn-purple">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Lihat PDF
        </a>

        <!-- Tombol Kembali -->
        <a href="{{ route('petugas.laporan.index', request()->query()) }}" class="btn btn-success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>

        <!-- Tombol Tutup -->
        <button onclick="window.close()" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tutup
        </button>
    </div>

    <!-- Konten yang akan di-print -->
    <div class="print-content">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN PEMINJAMAN ALAT</h1>
            <h2>Periode: {{ $periodTitle }}</h2>
            <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Statistik -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Peminjaman</h3>
                <p>{{ $stats['total_peminjaman'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Menunggu</h3>
                <p>{{ $stats['menunggu'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Disetujui</h3>
                <p>{{ $stats['disetujui'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Dipinjam</h3>
                <p>{{ $stats['dipinjam'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Pengajuan Pengembalian</h3>
                <p>{{ $stats['pengajuan_pengembalian'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Dikembalikan</h3>
                <p>{{ $stats['dikembalikan'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Ditolak</h3>
                <p>{{ $stats['ditolak'] }}</p>
            </div>
            <div class="stat-card" style="background: #fff3cd;">
                <h3>Total Denda</h3>
                <p style="color: #dc2626;">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Detail Peminjaman -->
        <h3 class="section-title">Detail Peminjaman</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Peminjam</th>
                    <th style="width: 20%;">Alat</th>
                    <th style="width: 8%;">Jumlah</th>
                    <th style="width: 12%;">Tgl Pinjam</th>
                    <th style="width: 12%;">Tgl Kembali</th>
                    <th style="width: 10%;">Denda</th>
                    <th style="width: 8%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>#{{ $item->id }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->alat->nama_alat ?? $item->alat->nama ?? 'N/A' }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                        <td style="text-align: right;">
                            {{ $item->denda > 0 ? 'Rp ' . number_format($item->denda, 0, ',', '.') : '-' }}
                        </td>
                        <td>
                            @switch($item->status)
                                @case('menunggu')
                                    <span class="badge badge-yellow">Menunggu</span>
                                    @break
                                @case('disetujui')
                                    <span class="badge badge-blue">Disetujui</span>
                                    @break
                                @case('dipinjam')
                                    <span class="badge badge-green">Dipinjam</span>
                                    @break
                                @case('pengajuan_pengembalian')
                                    <span class="badge badge-purple">Pengajuan</span>
                                    @break
                                @case('dikembalikan')
                                    <span class="badge badge-gray">Dikembalikan</span>
                                    @break
                                @case('ditolak')
                                    <span class="badge badge-red">Ditolak</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 20px;">
                            Tidak ada data peminjaman untuk periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer / Tanda Tangan -->
        <div class="footer">
            <p>Mengetahui,</p>
            <div class="signature">
                <p>_______________________</p>
                <p><strong>Petugas / Admin</strong></p>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>