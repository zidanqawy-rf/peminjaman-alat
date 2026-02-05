<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Petugas - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-emerald-50 to-emerald-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo -->
        <div class="mb-8">
            <h1 class="text-5xl font-bold text-emerald-700">Peminjaman Alat</h1>
        </div>

        <!-- Login Card -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg rounded-lg">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-emerald-100 rounded-full p-6">
                        <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v10a2 2 0 002 2h5m0 0h5a2 2 0 002-2V8a2 2 0 00-2-2h-5m0 0V5a2 2 0 10-4 0v1m4 0a1 1 0 00-1-1H9a1 1 0 00-1 1v1m4 0V5a2 2 0 10-4 0v1m0 0H5m15 0h.01M4 12h4m12 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Login Petugas</h2>
                <p class="text-gray-600 text-sm mt-2">Masukkan kredensial petugas Anda</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('petugas.login.post') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password" />
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                    Masuk
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Bukan petugas?
                    <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">Login
                        User</a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-6">
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>
