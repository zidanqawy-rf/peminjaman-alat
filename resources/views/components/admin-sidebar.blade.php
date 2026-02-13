<!-- Sidebar -->
<div x-data="{ open: false }">

    {{-- ===== DESKTOP SIDEBAR (Fixed) ===== --}}
    <div class="hidden md:flex md:flex-col fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white z-30">

        <!-- Logo/Brand -->
        <div class="flex items-center justify-center h-20 border-b border-slate-700 flex-shrink-0">
            <h1 class="text-2xl font-bold">Peminjaman Alat</h1>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-8 space-y-4 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span class="font-medium">User</span>
            </a>

            <a href="{{ route('admin.alat.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.alat.*') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                    </path>
                </svg>
                <span class="font-medium">Kelola Alat</span>
            </a>

            <a href="{{ route('admin.log-aktivitas.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.log-aktivitas.*') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                <span class="font-medium">Log Aktivitas</span>
            </a>
        </nav>

        <!-- User Profile Section -->
        <div class="border-t border-slate-700 p-4 flex-shrink-0">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-600 ring-2 ring-blue-500">
                        <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-300">Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-slate-700 hover:bg-red-600 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Spacer agar konten utama tidak tertimpa sidebar desktop --}}
    <div class="hidden md:block md:w-64 flex-shrink-0"></div>


    {{-- ===== MOBILE TOPBAR ===== --}}
    <div class="md:hidden fixed top-0 left-0 right-0 z-30 flex items-center justify-between bg-gradient-to-r from-slate-800 to-slate-900 text-white p-4 shadow-lg">
        <div class="flex items-center">
            <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h1 class="text-lg font-bold">Peminjaman Alat</h1>
        </div>
        <button @click="open = !open" class="text-white hover:text-blue-200 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    {{-- Spacer agar konten utama tidak tertimpa topbar mobile --}}
    <div class="md:hidden h-16"></div>


    {{-- ===== MOBILE OVERLAY ===== --}}
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

    {{-- ===== MOBILE SIDEBAR DRAWER ===== --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         @click.outside="open = false"
         class="fixed md:hidden left-0 top-0 w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white h-screen shadow-2xl z-50 flex flex-col"
         style="display: none;">

        <!-- Mobile Header -->
        <div class="flex items-center justify-between h-20 px-4 border-b border-slate-700 flex-shrink-0">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h1 class="text-lg font-bold">Peminjaman Alat</h1>
            </div>
            <button @click="open = false" class="text-white hover:text-blue-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span class="font-medium">User</span>
            </a>

            <a href="{{ route('admin.alat.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.alat.*') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                    </path>
                </svg>
                <span class="font-medium">Kelola Alat</span>
            </a>

            <a href="{{ route('admin.log-aktivitas.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.log-aktivitas.*') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                <span class="font-medium">Log Aktivitas</span>
            </a>
        </nav>

        <!-- Mobile User Profile -->
        <div class="border-t border-slate-700 p-4 flex-shrink-0">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-600 ring-2 ring-blue-500">
                        <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-300">Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-slate-700 hover:bg-red-600 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

</div>