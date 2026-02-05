<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Petugas - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div x-data="{ open: false }" class="flex">
            <!-- Sidebar for Desktop -->
            <div class="hidden md:flex md:flex-col md:w-64 bg-gradient-to-b from-emerald-800 to-emerald-900 text-white">
                <!-- Logo/Brand -->
                <div class="flex items-center justify-center h-20 border-b border-emerald-700">
                    <h1 class="text-2xl font-bold">Peminjaman Alat</h1>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-8 space-y-4">
                    <a href="{{ route('petugas.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors bg-emerald-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3">
                            </path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors hover:bg-emerald-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4"></path>
                        </svg>
                        <span class="font-medium">Peminjaman</span>
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors hover:bg-emerald-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Pengembalian</span>
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors hover:bg-emerald-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="font-medium">Laporan</span>
                    </a>
                </nav>

                <!-- User Profile Section -->
                <div class="border-t border-emerald-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-emerald-600">
                                <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-300">Petugas</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('petugas.logout') }}" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-emerald-700 rounded-lg transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Sidebar Toggle -->
            <div class="md:hidden w-full flex items-center bg-white border-b border-gray-200 p-4">
                <button @click="open = !open" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="ml-4 text-xl font-bold text-gray-800">Peminjaman Alat</h1>
            </div>

            <!-- Mobile Sidebar Menu -->
            <div x-show="open" @click.outside="open = false"
                class="fixed md:hidden left-0 top-0 w-64 bg-emerald-800 text-white h-screen shadow-lg z-50">
                <div class="flex items-center justify-between h-20 px-4 border-b border-emerald-700">
                    <h1 class="text-xl font-bold">Peminjaman Alat</h1>
                    <button @click="open = false" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <nav class="px-4 py-6 space-y-2">
                    <a href="{{ route('petugas.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg bg-emerald-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-emerald-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4"></path>
                        </svg>
                        <span>Peminjaman</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-emerald-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Pengembalian</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-emerald-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Laporan</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full md:w-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Dashboard Petugas') }}</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-4 md:p-6">
                <!-- Welcome Card -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Greeting Card -->
                    <div
                        class="md:col-span-2 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-8 text-white">
                        <h3 class="text-3xl font-bold mb-2">Selamat datang, Petugas 👋</h3>
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
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                        <a href="#"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-semibold text-lg">Peminjaman Baru</span>
                        </a>

                        <!-- Pengembalian Button -->
                        <a href="#"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold text-lg">Pengembalian</span>
                        </a>

                        <!-- Laporan Button -->
                        <a href="#"
                            class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="font-semibold text-lg">Lihat Laporan</span>
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
                                <p class="text-3xl font-bold text-gray-800">0</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Pengembalian Hari Ini</p>
                                <p class="text-3xl font-bold text-gray-800">0</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                <p class="text-3xl font-bold text-gray-800">0</p>
                            </div>
                            <div class="bg-yellow-100 rounded-full p-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                <p class="text-3xl font-bold text-gray-800">0</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
