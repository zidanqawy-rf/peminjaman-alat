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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Peminjaman Button -->
            <a href="{{ route('petugas.peminjaman.index') }}?status=disetujui"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-semibold text-lg">Proses Peminjaman</span>
            </a>

            <!-- Pengembalian Button -->
            <a href="{{ route('petugas.peminjaman.index') }}?status=dipinjam"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-lg">Proses Pengembalian</span>
            </a>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
    </div>
</x-petugas-layout>