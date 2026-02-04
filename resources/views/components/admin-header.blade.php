<!-- Top Navigation Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex justify-between items-center px-6 py-4">
        <div class="flex items-center space-x-4">
            @if ($showBackButton ?? false)
                <a href="{{ $backUrl ?? '#' }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            @endif
            <h2 class="text-2xl font-bold text-gray-800">{{ $title ?? 'Admin' }}</h2>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600">{{ Auth::user()->email }}</span>
        </div>
    </div>
</header>
