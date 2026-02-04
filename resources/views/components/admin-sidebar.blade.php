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
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <nav class="px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3">
                    </path>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-600' : 'hover:bg-slate-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span>User</span>
            </a>
        </nav>
    </div>
</div>
