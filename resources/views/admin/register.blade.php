<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tambah User - {{ config('app.name', 'Laravel') }}</title>

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
            <div class="hidden md:flex md:flex-col md:w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white">
                <!-- Logo/Brand -->
                <div class="flex items-center justify-center h-20 border-b border-slate-700">
                    <h1 class="text-2xl font-bold">Peminjaman Alat</h1>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-8 space-y-4">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors hover:bg-slate-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3">
                            </path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.register') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors bg-blue-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                        <span class="font-medium">Tambah User</span>
                    </a>
                </nav>

                <!-- User Profile Section -->
                <div class="border-t border-slate-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-600">
                                <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">Admin</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-slate-700 rounded-lg transition-colors">
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
                class="fixed md:hidden left-0 top-0 w-64 bg-slate-800 text-white h-screen shadow-lg z-50">
                <div class="flex items-center justify-between h-20 px-4 border-b border-slate-700">
                    <h1 class="text-xl font-bold">Peminjaman Alat</h1>
                    <button @click="open = false" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <nav class="px-4 py-6 space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.register') }}" class="flex items-center px-4 py-3 rounded-lg bg-blue-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                        <span>Tambah User</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full md:w-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Tambah User Baru') }}</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-4 md:p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <!-- Form Header -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Buat User Baru</h3>
                            <p class="text-gray-600">Isi form di bawah untuk menambahkan user baru ke sistem.</p>
                        </div>

                        <!-- Form -->
                        <form method="POST" action="{{ route('admin.register.post') }}" class="space-y-6">
                            @csrf

                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-800 mb-2">
                                    Nama Lengkap
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap" />
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">
                                    Email
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                    placeholder="contoh@email.com" />
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">
                                    Password
                                </label>
                                <input type="password" id="password" name="password" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                    placeholder="Masukkan password" />
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-semibold text-gray-800 mb-2">
                                    Konfirmasi Password
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Konfirmasi password" />
                            </div>

                            <!-- Role Field -->
                            <div>
                                <label for="role" class="block text-sm font-semibold text-gray-800 mb-2">
                                    Role / Peran
                                </label>
                                <select id="role" name="role"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('role') border-red-500 @enderror">
                                    <option value="">Pilih Role</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="flex gap-4 pt-4">
                                <button type="submit"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Buat User
                                </button>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 transition-colors text-center">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
