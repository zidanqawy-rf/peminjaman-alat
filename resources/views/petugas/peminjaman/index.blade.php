<x-petugas-layout>
    <x-slot name="title">Kelola Peminjaman</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Kelola Peminjaman</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-6 rounded-md border-l-4 border-green-500 bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 rounded-md border-l-4 border-red-500 bg-red-50 p-4">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
            @endif

            <!-- STATS: 3 kolom pakai inline style agar tidak terpotong Tailwind purge -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-blue-500 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Total</p><p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-yellow-500 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Menunggu</p><p class="text-2xl font-bold text-gray-900">{{ $stats['menunggu'] }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-blue-600 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Disetujui</p><p class="text-2xl font-bold text-gray-900">{{ $stats['disetujui'] }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-green-500 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Dipinjam</p><p class="text-2xl font-bold text-gray-900">{{ $stats['dipinjam'] }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-purple-500 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Pengajuan</p><p class="text-2xl font-bold text-gray-900">{{ $stats['pengajuan_pengembalian'] }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow border-2 border-red-300">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-red-600 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Di Denda</p><p class="text-2xl font-bold text-gray-900">{{ $stats['di_denda'] ?? 0 }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-gray-500 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Dikembalikan</p><p class="text-2xl font-bold text-gray-900">{{ $stats['dikembalikan'] }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-red-500 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Ditolak</p><p class="text-2xl font-bold text-gray-900">{{ $stats['ditolak'] }}</p></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow border-2 border-orange-300">
                    <div class="p-5 flex items-center gap-3">
                        <div class="flex-shrink-0 rounded-md bg-orange-500 p-2">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-gray-500">Perlu Verif</p><p class="text-2xl font-bold text-gray-900">{{ $stats['menunggu_verifikasi_bayar'] }}</p></div>
                    </div>
                </div>

            </div>

            <!-- Filter & Search -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('petugas.peminjaman.index') }}" class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Nama user atau alat..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Peminjaman</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="pengajuan_pengembalian" {{ request('status') == 'pengajuan_pengembalian' ? 'selected' : '' }}>Pengajuan Pengembalian</option>
                                <option value="di_denda" {{ request('status') == 'di_denda' ? 'selected' : '' }}>Di Denda</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label for="pembayaran" class="block text-sm font-medium text-gray-700">Status Pembayaran Denda</label>
                            <select name="pembayaran" id="pembayaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">Semua</option>
                                <option value="ada_denda" {{ request('pembayaran') == 'ada_denda' ? 'selected' : '' }}>Ada Denda</option>
                                <option value="menunggu_verifikasi" {{ request('pembayaran') == 'menunggu_verifikasi' ? 'selected' : '' }}>⏳ Menunggu Verifikasi</option>
                                <option value="terverifikasi" {{ request('pembayaran') == 'terverifikasi' ? 'selected' : '' }}>✓ Sudah Terverifikasi</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('petugas.peminjaman.index') }}"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Peminjaman -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Denda</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($peminjaman as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">#{{ $item->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->items->isNotEmpty())
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ optional($item->items->first()->alat)->nama ?? 'N/A' }}
                                            </div>
                                            @if($item->items->count() > 1)
                                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">
                                                +{{ $item->items->count() - 1 }} lainnya
                                            </span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $item->items->sum('jumlah') }} unit
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->denda > 0)
                                            <div class="text-sm">
                                                <p class="font-semibold text-red-600">Rp {{ number_format($item->denda, 0, ',', '.') }}</p>
                                                @if($item->status_pembayaran_denda === 'terverifikasi')
                                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">✓ Terverifikasi</span>
                                                @elseif($item->status_pembayaran_denda === 'menunggu_verifikasi')
                                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">⏳ Verifikasi</span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Belum bayar</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $sb = [
                                                'menunggu'               => ['yellow', 'Menunggu'],
                                                'disetujui'              => ['blue',   'Disetujui'],
                                                'dipinjam'               => ['green',  'Dipinjam'],
                                                'pengajuan_pengembalian' => ['purple', 'Pengajuan'],
                                                'di_denda'               => ['red',    'Di Denda'],
                                                'dikembalikan'           => ['gray',   'Dikembalikan'],
                                                'ditolak'                => ['red',    'Ditolak'],
                                            ];
                                            [$bc, $bl] = $sb[$item->status] ?? ['gray', ucfirst($item->status)];
                                        @endphp
                                        <span class="inline-flex rounded-full bg-{{ $bc }}-100 px-2 py-1 text-xs font-semibold leading-5 text-{{ $bc }}-800">
                                            {{ $bl }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('petugas.peminjaman.show', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-900">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-10 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                                        <p class="mt-1 text-sm text-gray-500">Belum ada peminjaman yang tersedia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($peminjaman->hasPages())
                    <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                        {{ $peminjaman->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-petugas-layout>