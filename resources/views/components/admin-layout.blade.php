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

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <x-admin-sidebar />

        <!-- Main Content Area -->
        <div class="md:ml-64 min-h-screen flex flex-col">

            <!-- Optional page header slot (used by master denda, etc.) -->
            @if(isset($header))
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-4 md:px-6 py-4">
                    {{ $header }}
                </div>
            </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>