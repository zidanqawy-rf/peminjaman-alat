<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Peminjaman - {{ $periodTitle }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header h2 {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 11px;
            color: #888;
        }

        .stats {
            margin-bottom: 20px;
            width: 100%;
        }

        .stats table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats td {
            width: 25%;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            vertical-align: top;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
            display: block;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .stat-denda {
            color: #dc2626;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.data-table thead {
            background: #333;
            color: white;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 6px 4px;
            text-align: left;
            font-size: 9px;
        }

        table.data-table th {
            text-transform: uppercase;
            font-weight: bold;
        }

        table.data-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-purple { background: #e9d5ff; color: #6b21a8; }
        .badge-gray { background: #e5e7eb; color: #374151; }
        .badge-red { background: #fee2e2; color: #991b1b; }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature {
            margin-top: 50px;
            text-align: center;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0 8px 0;
            padding-bottom: 3px;
            border-bottom: 2px solid #333;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PEMINJAMAN ALAT</h1>
        <h2>Periode: {{ $periodTitle }}</h2>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Statistik -->
    <div class="stats">
        <table>
            <tr>
                <td>
                    <span class="stat-label">Total Peminjaman</span>
                    <span class="stat-value">{{ $stats['total_peminjaman'] }}</span>
                </td>
                <td>
                    <span class="stat-label">Menunggu</span>
                    <span class="stat-value">{{ $stats['menunggu'] }}</span>
                </td>
                <td>
                    <span class="stat-label">Disetujui</span>
                    <span class="stat-value">{{ $stats['disetujui'] }}</span>
                </td>
                <td>
                    <span class="stat-label">Dipinjam</span>
                    <span class="stat-value">{{ $stats['dipinjam'] }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="stat-label">Pengajuan Pengembalian</span>
                    <span class="stat-value">{{ $stats['pengajuan_pengembalian'] }}</span>
                </td>
                <td>
                    <span class="stat-label">Dikembalikan</span>
                    <span class="stat-value">{{ $stats['dikembalikan'] }}</span>
                </td>
                <td>
                    <span class="stat-label">Ditolak</span>
                    <span class="stat-value">{{ $stats['ditolak'] }}</span>
                </td>
                <td>
                    <span class="stat-label">Total Denda</span>
                    <span class="stat-value stat-denda">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Detail Peminjaman -->
    <h3 class="section-title">Detail Peminjaman</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 5%;">ID</th>
                <th style="width: 18%;">Peminjam</th>
                <th style="width: 18%;">Alat</th>
                <th style="width: 7%;">Jumlah</th>
                <th style="width: 11%;">Tgl Pinjam</th>
                <th style="width: 11%;">Tgl Kembali</th>
                <th style="width: 13%;">Denda</th>
                <th style="width: 13%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>#{{ $item->id }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->alat->nama_alat ?? $item->alat->nama ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $item->jumlah }}</td>
                    <td>{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: right; font-weight: bold; color: #dc2626;">
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
                    <td colspan="9" style="text-align: center; padding: 15px;">
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
</body>
</html>