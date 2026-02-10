<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Daftar Peminjaman Saya
            </h2>
            <a href="{{ route('peminjaman.create') }}"
                class="transform rounded-md bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 text-sm font-medium text-white transition-all hover:scale-105 hover:shadow-lg">
                + Buat Peminjaman Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Pesan Sukses -->
            @if (session('success'))
                <div class="mb-6 rounded-md border-l-4 border-green-500 bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistik Ringkas -->
            <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Peminjaman -->
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
                                    <dd class="text-lg font-semibold text-gray-900">{{ $peminjaman->total() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Persetujuan -->
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-yellow-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Menunggu</dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ $peminjaman->where('status', 'menunggu')->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dipinjam -->
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-green-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Dipinjam</dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ $peminjaman->where('status', 'dipinjam')->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-red-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Ditolak</dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ $peminjaman->where('status', 'ditolak')->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Peminjaman -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($peminjaman as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $peminjaman->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->alat->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->alat->kategori }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $item->jumlah }} unit
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $item->tanggal_pinjam->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $item->tanggal_kembali->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @switch($item->status)
                                            @case('menunggu')
                                                <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold leading-5 text-yellow-800">
                                                    Menunggu Persetujuan
                                                </span>
                                                @break
                                            @case('disetujui')
                                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold leading-5 text-blue-800">
                                                    Disetujui
                                                </span>
                                                @break
                                            @case('dipinjam')
                                                <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold leading-5 text-green-800">
                                                    Sedang Dipinjam
                                                </span>
                                                @break
                                            @case('dikembalikan')
                                                <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold leading-5 text-gray-800">
                                                    Dikembalikan
                                                </span>
                                                @break
                                            @case('ditolak')
                                                <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold leading-5 text-red-800">
                                                    Ditolak
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('peminjaman.show', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peminjaman</h3>
                                        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat peminjaman baru.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('peminjaman.create') }}"
                                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                                + Buat Peminjaman Baru
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($peminjaman->hasPages())
                    <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                        {{ $peminjaman->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>