<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard Petugas' }} - {{ config('app.name', 'Laravel') }}</title>

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
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('petugas.dashboard') ? 'bg-emerald-600' : 'hover:bg-emerald-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('petugas.peminjaman.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('petugas.peminjaman.*') ? 'bg-emerald-600' : 'hover:bg-emerald-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Kelola Peminjaman</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full md:w-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $header ?? 'Dashboard Petugas' }}</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>