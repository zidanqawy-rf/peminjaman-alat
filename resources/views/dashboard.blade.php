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
                    <button
                        class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span class="font-semibold text-lg">Pinjam Alat</span>
                    </button>

                    <!-- View Borrows Button -->
                    <button
                        class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold text-lg">Peminjaman Saya</span>
                    </button>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature Card 1 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m0 0h6m-6-12h6m0 0v6m0-6h-6">
                            </path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Pinjam Alat</h5>
                    <p class="text-gray-600 text-sm">Temukan dan pinjam alat yang Anda butuhkan dengan mudah.</p>
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
                    <p class="text-gray-600 text-sm">Pantau status peminjaman alat Anda secara real-time.</p>
                </div>

                <!-- Feature Card 3 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l-4 4m0 0l-4-4m4 4v-12"></path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Kembalikan Alat</h5>
                    <p class="text-gray-600 text-sm">Kembalikan alat dengan mudah melalui sistem kami.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
