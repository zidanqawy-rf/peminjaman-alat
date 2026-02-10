<x-petugas-layout>
    <x-slot name="title">Dashboard Petugas</x-slot>
    <x-slot name="header">Dashboard Petugas</x-slot>

    <!-- Welcome Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Greeting Card -->
        <div class="md:col-span-2 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-8 text-white">
            <h3 class="text-3xl font-bold mb-2">Selamat datang, {{ Auth::user()->name }} 👋</h3>
            <p class="text-emerald-100">Kelola peminjaman dan pengembalian alat dengan efisien.</p>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Status Aktif</p>
                    <p class="text-3xl font-bold text-emerald-600">✓</p>
                </div>
                <div class="bg-emerald-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h4 class="text-xl font-bold text-gray-800 mb-6">Aksi Cepat</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Peminjaman Button -->
            <a href="{{ route('petugas.peminjaman.index') }}?status=menunggu"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-lg">Approve Peminjaman</span>
            </a>

            <!-- Pengembalian Button -->
            <a href="{{ route('petugas.peminjaman.index') }}?status=pengajuan_pengembalian"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                </svg>
                <span class="font-semibold text-lg">Proses Pengembalian</span>
            </a>

            <!-- Verifikasi Denda Button -->
            <a href="{{ route('petugas.peminjaman.index') }}?pembayaran=menunggu_verifikasi"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-lg">Verifikasi Denda</span>
            </a>
        </div>
    </div>

    <!-- Statistics Section -->
    @php
        $perluVerifikasiDenda = \App\Models\Peminjaman::where('status_pembayaran_denda', 'menunggu_verifikasi')->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Peminjaman Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $peminjamanHariIni }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pengembalian Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pengembalianHariIni }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menunggu Pengembalian</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $menungguPengembalian }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Alat Tersedia</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $alatTersedia }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- STAT CARD BARU: Verifikasi Denda -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow {{ $perluVerifikasiDenda > 0 ? 'ring-2 ring-orange-400' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Perlu Verifikasi</p>
                    <p class="text-3xl font-bold {{ $perluVerifikasiDenda > 0 ? 'text-orange-600' : 'text-gray-400' }}">
                        {{ $perluVerifikasiDenda }}
                    </p>
                    @if($perluVerifikasiDenda > 0)
                        <a href="{{ route('petugas.peminjaman.index') }}?pembayaran=menunggu_verifikasi" 
                           class="text-xs text-orange-600 hover:text-orange-800 font-medium mt-1 inline-block">
                            Verifikasi →
                        </a>
                    @endif
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Verifikasi Denda (jika ada) -->
    @if($perluVerifikasiDenda > 0)
        @php
            $peminjamanPerluVerifikasi = \App\Models\Peminjaman::with(['user', 'alat'])
                ->where('status_pembayaran_denda', 'menunggu_verifikasi')
                ->latest('updated_at')
                ->limit(5)
                ->get();
        @endphp
        
        <div class="mt-8 bg-orange-50 border-l-4 border-orange-500 p-6 rounded-lg shadow-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-semibold text-orange-900 mb-2">💰 Pembayaran Denda Menunggu Verifikasi</h3>
                    <p class="text-sm text-orange-800 mb-3">
                        Ada <strong>{{ $perluVerifikasiDenda }} pembayaran denda</strong> yang perlu diverifikasi.
                    </p>
                    <div class="space-y-2">
                        @foreach($peminjamanPerluVerifikasi as $item)
                            <div class="bg-white p-3 rounded border border-orange-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $item->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $item->alat->nama }}</p>
                                        <p class="text-xs text-gray-500">
                                            Diupload {{ $item->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-orange-600">Rp {{ number_format($item->denda, 0, ',', '.') }}</p>
                                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">Menunggu</span>
                                    </div>
                                </div>
                                <a href="{{ route('petugas.peminjaman.show', $item) }}" 
                                   class="text-xs text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                                    Verifikasi Sekarang →
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($perluVerifikasiDenda > 5)
                        <a href="{{ route('petugas.peminjaman.index') }}?pembayaran=menunggu_verifikasi" 
                           class="mt-3 inline-block text-sm text-orange-700 hover:text-orange-900 font-medium">
                            Lihat Semua ({{ $perluVerifikasiDenda }}) →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</x-petugas-layout>