<x-petugas-layout>
    <x-slot name="title">Laporan Peminjaman</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Peminjaman
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Filter Period -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Filter Periode Laporan</h3>
                    
                    <form method="GET" action="{{ route('petugas.laporan.index') }}" class="space-y-4">
                        <!-- Filter Type Selection -->
                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Filter</label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="filter_type" value="hari" 
                                            {{ $filterType == 'hari' ? 'checked' : '' }}
                                            onchange="toggleFilterInputs(this.value)"
                                            class="form-radio h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-700">Hari</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="filter_type" value="bulan" 
                                            {{ $filterType == 'bulan' ? 'checked' : '' }}
                                            onchange="toggleFilterInputs(this.value)"
                                            class="form-radio h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-700">Bulan</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="filter_type" value="tahun" 
                                            {{ $filterType == 'tahun' ? 'checked' : '' }}
                                            onchange="toggleFilterInputs(this.value)"
                                            class="form-radio h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-700">Tahun</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Filter Inputs -->
                        <div class="grid gap-4 md:grid-cols-3">
                            <!-- Filter Hari -->
                            <div id="filter-hari" style="display: {{ $filterType == 'hari' ? 'block' : 'none' }}">
                                <label for="filter_date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                                <input type="date" name="filter_date" id="filter_date" 
                                    value="{{ $filterDate }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Filter Bulan -->
                            <div id="filter-bulan" style="display: {{ $filterType == 'bulan' ? 'block' : 'none' }}">
                                <label for="filter_month" class="block text-sm font-medium text-gray-700">Pilih Bulan</label>
                                <input type="month" name="filter_month" id="filter_month" 
                                    value="{{ $filterMonth }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Filter Tahun -->
                            <div id="filter-tahun" style="display: {{ $filterType == 'tahun' ? 'block' : 'none' }}">
                                <label for="filter_year" class="block text-sm font-medium text-gray-700">Pilih Tahun</label>
                                <select name="filter_year" id="filter_year"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @for($year = now()->year; $year >= now()->year - 5; $year--)
                                        <option value="{{ $year }}" {{ $filterYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <button type="submit"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Tampilkan Laporan
                            </button>
                            
                            <a href="{{ route('petugas.laporan.index') }}"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Reset
                            </a>

                            <button type="button" onclick="printLaporan()"
                                class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Cetak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Period Title -->
            <div class="mb-6 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white shadow-lg">
                <h3 class="text-2xl font-bold">Laporan Periode: {{ $periodTitle }}</h3>
                <p class="mt-1 text-sm opacity-90">Ringkasan data peminjaman alat</p>
            </div>

            <!-- Statistik Cards -->
            <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-blue-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Peminjaman</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_peminjaman'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-green-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Dikembalikan</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['dikembalikan'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-purple-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Sedang Dipinjam</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['dipinjam'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-orange-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Denda</dt>
                                    <dd class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4">
                    <p class="text-sm text-yellow-600 font-medium">Menunggu Persetujuan</p>
                    <p class="mt-1 text-2xl font-bold text-yellow-900">{{ $stats['menunggu'] }}</p>
                </div>
                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                    <p class="text-sm text-blue-600 font-medium">Disetujui</p>
                    <p class="mt-1 text-2xl font-bold text-blue-900">{{ $stats['disetujui'] }}</p>
                </div>
                <div class="rounded-lg bg-purple-50 border border-purple-200 p-4">
                    <p class="text-sm text-purple-600 font-medium">Pengajuan Pengembalian</p>
                    <p class="mt-1 text-2xl font-bold text-purple-900">{{ $stats['pengajuan_pengembalian'] }}</p>
                </div>
                <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                    <p class="text-sm text-red-600 font-medium">Ditolak</p>
                    <p class="mt-1 text-2xl font-bold text-red-900">{{ $stats['ditolak'] }}</p>
                </div>
            </div>

            <!-- Top 10 Alat Paling Sering Dipinjam -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Top 10 Alat Paling Sering Dipinjam</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Alat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total Dipinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Sedang Dipinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Dikembalikan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($alatStats as $index => $stat)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $stat['alat']->nama_alat ?? $stat['alat']->nama ?? 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $stat['total'] }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $stat['dipinjam'] }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $stat['dikembalikan'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top 10 Peminjam Terbanyak -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Top 10 Peminjam Terbanyak</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total Peminjaman</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total Denda</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($userStats as $index => $stat)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $stat['user']->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $stat['user']->email }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $stat['total'] }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-red-600">
                                            Rp {{ number_format($stat['denda'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detail Semua Peminjaman -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Detail Semua Peminjaman</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Alat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Denda</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($peminjaman as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">#{{ $item->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->alat->nama_alat ?? $item->alat->nama ?? 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->jumlah }} unit</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                            {{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                            {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-red-600">
                                            {{ $item->denda > 0 ? 'Rp ' . number_format($item->denda, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @switch($item->status)
                                                @case('menunggu')
                                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">Menunggu</span>
                                                    @break
                                                @case('disetujui')
                                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">Disetujui</span>
                                                    @break
                                                @case('dipinjam')
                                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">Dipinjam</span>
                                                    @break
                                                @case('pengajuan_pengembalian')
                                                    <span class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-semibold text-purple-800">Pengajuan</span>
                                                    @break
                                                @case('dikembalikan')
                                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">Dikembalikan</span>
                                                    @break
                                                @case('ditolak')
                                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">Ditolak</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-10 text-center">
                                            <p class="text-sm text-gray-500">Tidak ada data peminjaman untuk periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFilterInputs(type) {
            // Hide all filter inputs
            document.getElementById('filter-hari').style.display = 'none';
            document.getElementById('filter-bulan').style.display = 'none';
            document.getElementById('filter-tahun').style.display = 'none';
            
            // Show selected filter input
            document.getElementById('filter-' + type).style.display = 'block';
        }

        function printLaporan() {
            const params = new URLSearchParams(window.location.search);
            const printUrl = '{{ route("petugas.laporan.print") }}?' + params.toString();
            window.open(printUrl, '_blank');
        }
    </script>
    @endpush
</x-petugas-layout>