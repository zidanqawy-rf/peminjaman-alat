<x-admin-layout title="Log Aktivitas">
    {{-- 
        Wrapper utama: md:ml-64 untuk memberi ruang sidebar fixed (w-64) di desktop.
        Di mobile, sidebar tidak fixed (drawer), jadi tidak perlu margin.
    --}}
    <div class="md:ml-64 min-h-screen bg-gray-100">
        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Log Aktivitas</h2>
                    <p class="text-gray-600 mt-1">Monitor semua aktivitas peminjaman alat</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="GET" action="{{ route('admin.log-aktivitas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama user, kelas, alat..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="pengajuan_pengembalian" {{ request('status') == 'pengajuan_pengembalian' ? 'selected' : '' }}>Pengajuan Pengembalian</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Buttons -->
                    <div class="md:col-span-4 flex items-center space-x-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.log-aktivitas.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Log Activities List -->
            <div class="bg-white rounded-lg shadow-md p-6">
                @if($logAktivitas->count() > 0)
                    <div class="space-y-4">
                        @foreach($logAktivitas as $log)
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-full 
                                        {{ $log['status'] === 'selesai' || $log['status'] === 'dikembalikan' ? 'bg-green-100' : 
                                           ($log['status'] === 'ditolak' ? 'bg-red-100' : 
                                           ($log['status'] === 'dipinjam' ? 'bg-purple-100' : 'bg-blue-100')) }}">
                                        @if($log['status'] === 'selesai' || $log['status'] === 'dikembalikan')
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @elseif($log['status'] === 'ditolak')
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @elseif($log['status'] === 'dipinjam')
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $log['user_name'] }}
                                                <span class="text-gray-500 font-normal">- {{ $log['user_kelas'] }}</span>
                                            </p>
                                            <p class="text-sm text-gray-700 mt-1">
                                                <span class="font-medium">{{ $log['alat_name'] }}</span>
                                                <span class="text-gray-500 text-xs">({{ $log['jumlah'] }} unit)</span>
                                            </p>
                                            @if($log['keperluan'])
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span class="font-medium">Keperluan:</span> {{ Str::limit($log['keperluan'], 50) }}
                                                </p>
                                            @endif
                                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($log['tanggal_pinjam'])->format('d M Y') }}
                                                @if($log['tanggal_kembali'])
                                                    → {{ \Carbon\Carbon::parse($log['tanggal_kembali'])->format('d M Y') }}
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Status & Time -->
                                        <div class="text-right ml-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log['status_color'] }}">
                                                {{ $log['status_text'] }}
                                            </span>
                                            <p class="text-xs text-gray-500 mt-2">{{ $log['time_ago'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $logAktivitas->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500">Tidak ada aktivitas peminjaman yang ditemukan</p>
                        @if(request()->hasAny(['search', 'status', 'tanggal_dari', 'tanggal_sampai']))
                            <a href="{{ route('admin.log-aktivitas.index') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                Reset filter untuk melihat semua data
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>