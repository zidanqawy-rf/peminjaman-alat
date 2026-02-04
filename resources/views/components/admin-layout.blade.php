<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin' }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-admin-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full md:w-auto">
            <!-- Header -->
            <x-admin-header :title="$title ?? 'Admin'" :showBackButton="$showBackButton ?? false" :backUrl="$backUrl ?? '#'" />

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
