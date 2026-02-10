<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-8 text-white">
                    <h3 class="text-3xl font-bold mb-2">Selamat datang, {{ Auth::user()->name }} 👋</h3>
                    <p class="text-blue-100">Kelola peminjaman alat Anda dengan mudah dan efisien.</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                <h4 class="text-xl font-bold text-gray-800 mb-6">Aksi Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Borrow Tools Button -->
                    <a href="{{ route('peminjaman.create') }}"
                        class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span class="font-semibold text-lg">Pinjam Alat</span>
                    </a>

                    <!-- View Borrows Button -->
                    <a href="{{ route('peminjaman.index') }}"
                        class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold text-lg">Peminjaman Saya</span>
                    </a>
                </div>
            </div>

            <!-- Statistics Cards (Optional) -->
            @php
                $totalPeminjaman = \App\Models\Peminjaman::where('user_id', Auth::id())->count();
                $menunggu = \App\Models\Peminjaman::where('user_id', Auth::id())->where('status', 'menunggu')->count();
                $dipinjam = \App\Models\Peminjaman::where('user_id', Auth::id())->where('status', 'dipinjam')->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Peminjaman -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Peminjaman</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPeminjaman }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Persetujuan -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Menunggu</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $menunggu }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full w-12 h-12 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sedang Dipinjam -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Sedang Dipinjam</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $dipinjam }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature Card 1 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Pinjam Alat</h5>
                    <p class="text-gray-600 text-sm mb-4">Temukan dan pinjam alat yang Anda butuhkan dengan mudah.</p>
                    <a href="{{ route('peminjaman.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Mulai Pinjam →
                    </a>
                </div>

                <!-- Feature Card 2 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Tracking Peminjaman</h5>
                    <p class="text-gray-600 text-sm mb-4">Pantau status peminjaman alat Anda secara real-time.</p>
                    <a href="{{ route('peminjaman.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Lihat Status →
                    </a>
                </div>

                <!-- Feature Card 3 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Riwayat Peminjaman</h5>
                    <p class="text-gray-600 text-sm mb-4">Lihat riwayat lengkap peminjaman alat Anda.</p>
                    <a href="{{ route('peminjaman.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                        Lihat Riwayat →
                    </a>
                </div>
            </div>

            <!-- Recent Borrowings (Optional) -->
            @php
                $recentPeminjaman = \App\Models\Peminjaman::with('alat')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->limit(5)
                    ->get();
            @endphp

            @if($recentPeminjaman->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-bold text-gray-800">Peminjaman Terbaru</h4>
                    <a href="{{ route('peminjaman.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentPeminjaman as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->alat->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->jumlah }} unit</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->tanggal_pinjam->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->tanggal_kembali->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($item->status)
                                        @case('menunggu')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Menunggu
                                            </span>
                                            @break
                                        @case('disetujui')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Disetujui
                                            </span>
                                            @break
                                        @case('dipinjam')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Dipinjam
                                            </span>
                                            @break
                                        @case('dikembalikan')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Dikembalikan
                                            </span>
                                            @break
                                        @case('ditolak')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>