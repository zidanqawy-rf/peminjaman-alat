<!-- Modal -->
<div x-show="open{{ $id ?? '' }}" @click="open{{ $id ?? '' }} = false"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display: none;">
    <div @click.stop class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-screen overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Modal' }}</h3>
            <button @click="open{{ $id ?? '' }} = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
