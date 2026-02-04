<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Peminjaman Alat') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-bold text-blue-600">Peminjaman Alat</h1>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 text-gray-800 hover:text-blue-600 transition-colors font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-gray-800 hover:text-blue-600 transition-colors font-medium">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="flex-1 py-20 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
                    <!-- Left Content -->
                    <div>
                        <h2 class="text-5xl font-bold text-gray-800 mb-6 leading-tight">
                            Kelola Peminjaman Alat Dengan Mudah
                        </h2>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            Sistem manajemen peminjaman alat yang modern, cepat, dan terpercaya. Kelola inventori alat
                            Anda dengan mudah dan pantau setiap peminjaman secara real-time.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-center">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-center">
                                    Login ke Sistem
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Right Image -->
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg p-8 text-white">
                        <div class="flex items-center justify-center h-80">
                            <div class="text-center">
                                <svg class="w-24 h-24 mx-auto mb-4 opacity-80" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <p class="text-xl font-semibold">Solusi Terpadu</p>
                                <p class="text-blue-100 mt-2">Kelola semua aspek peminjaman alat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="mb-16">
                    <h3 class="text-3xl font-bold text-gray-800 mb-12 text-center">Fitur Unggulan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow">
                            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-3">Manajemen Inventori</h4>
                            <p class="text-gray-600">Kelola stok alat secara efisien dengan sistem inventori yang
                                terintegrasi.</p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow">
                            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-3">Tracking Real-Time</h4>
                            <p class="text-gray-600">Pantau status peminjaman alat secara real-time dan dapatkan
                                notifikasi otomatis.</p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow">
                            <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-3">Manajemen Pengguna</h4>
                            <p class="text-gray-600">Kelola pengguna dengan role berbeda dan kontrol akses yang
                                granular.</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-12 text-white mb-16">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div>
                            <p class="text-4xl font-bold">∞</p>
                            <p class="text-blue-100 mt-2">Alat yang Dapat Dikelola</p>
                        </div>
                        <div>
                            <p class="text-4xl font-bold">24/7</p>
                            <p class="text-blue-100 mt-2">Akses Sistem</p>
                        </div>
                        <div>
                            <p class="text-4xl font-bold">100%</p>
                            <p class="text-blue-100 mt-2">Keamanan Data</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <h3 class="text-3xl font-bold text-gray-800 mb-6">Siap Untuk Memulai?</h3>
                    <p class="text-gray-600 mb-8 text-lg">Daftarkan akun Anda sekarang dan mulai kelola peminjaman alat
                        dengan mudah.</p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                Dashboard Saya
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                Login Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-12">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="mb-2">&copy; 2026 Peminjaman Alat. All rights reserved.</p>
                <p class="text-gray-400">Kelola peminjaman alat dengan mudah dan efisien.</p>
            </div>
        </footer>
    </div>
</body>

</html>
