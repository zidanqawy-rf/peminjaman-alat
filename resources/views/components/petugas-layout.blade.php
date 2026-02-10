@props(['title' => 'Dashboard Petugas', 'header' => 'Dashboard Petugas'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js (jika belum ada di app.js) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar -->
        <div x-data="{ open: false }" class="relative">
            
            <!-- Mobile Top Bar -->
            <div class="md:hidden w-full flex items-center justify-between bg-gradient-to-r from-emerald-800 to-emerald-900 text-white p-4 shadow-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h1 class="text-lg font-bold">Peminjaman Alat</h1>
                </div>
                <button @click="open = !open" class="text-white hover:text-emerald-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Sidebar -->
            <div class="hidden md:flex md:flex-col md:w-64 md:min-h-screen bg-gradient-to-b from-emerald-800 to-emerald-900 text-white shadow-xl">
                <!-- Logo/Brand -->
                <div class="flex items-center justify-center h-20 border-b border-emerald-700 px-4">
                    <svg class="w-10 h-10 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h1 class="text-xl font-bold">Peminjaman Alat</h1>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-8 space-y-2">
                    <a href="{{ route('petugas.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('petugas.dashboard') ? 'bg-emerald-600 shadow-lg' : 'hover:bg-emerald-700 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('petugas.peminjaman.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('petugas.peminjaman.*') ? 'bg-emerald-600 shadow-lg' : 'hover:bg-emerald-700 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-medium">Kelola Peminjaman</span>
                    </a>
                </nav>

                <!-- User Profile Section -->
                <div class="border-t border-emerald-700 p-4">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-emerald-600 ring-2 ring-emerald-500">
                                <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-emerald-300">Petugas</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-emerald-700 hover:bg-red-600 rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Sidebar Menu (Overlay) -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="open = false" 
                 class="fixed md:hidden inset-0 bg-black bg-opacity-50 z-40"
                 style="display: none;">
            </div>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 @click.outside="open = false"
                 class="fixed md:hidden left-0 top-0 w-64 bg-gradient-to-b from-emerald-800 to-emerald-900 text-white h-screen shadow-2xl z-50"
                 style="display: none;">
                
                <!-- Mobile Header -->
                <div class="flex items-center justify-between h-20 px-4 border-b border-emerald-700">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h1 class="text-lg font-bold">Peminjaman Alat</h1>
                    </div>
                    <button @click="open = false" class="text-white hover:text-emerald-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation -->
                <nav class="px-4 py-6 space-y-2">
                    <a href="{{ route('petugas.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('petugas.dashboard') ? 'bg-emerald-600' : 'hover:bg-emerald-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('petugas.peminjaman.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('petugas.peminjaman.*') ? 'bg-emerald-600' : 'hover:bg-emerald-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-medium">Kelola Peminjaman</span>
                    </a>
                </nav>

                <!-- Mobile User Profile -->
                <div class="absolute bottom-0 left-0 right-0 border-t border-emerald-700 p-4">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-emerald-600 ring-2 ring-emerald-500">
                                <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-emerald-300">Petugas</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-emerald-700 hover:bg-red-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
                <div class="flex justify-between items-center px-4 md:px-6 py-4">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $header }}</h2>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 bg-gray-50">
                {{ $slot }}
            </main>

            <!-- Footer (Optional) -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <p class="text-center text-sm text-gray-600">
                    &copy; {{ date('Y') }} Sistem Peminjaman Alat Lab. All rights reserved.
                </p>
            </footer>
        </div>
    </div>
</body>

</html>