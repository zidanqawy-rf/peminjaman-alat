<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftar Peminjaman Saya</h2>
            <a href="{{ route('peminjaman.create') }}"
               class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                + Buat Peminjaman Baru
            </a>
        </div>
    </x-slot>

    <style>
        .stat-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 16px !important;
            margin-bottom: 24px !important;
        }
    </style>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @foreach(['success'=>'green','warning'=>'yellow','error'=>'red'] as $type=>$color)
                @if(session($type))
                <div class="mb-6 rounded-md border-l-4 border-{{ $color }}-500 bg-{{ $color }}-50 p-4">
                    <p class="text-sm font-medium text-{{ $color }}-800">{{ session($type) }}</p>
                </div>
                @endif
            @endforeach

            @if($errors->any())
            <div class="mb-6 rounded-md border-l-4 border-red-500 bg-red-50 p-4">
                @foreach($errors->all() as $e)<p class="text-sm text-red-800">{{ $e }}</p>@endforeach
            </div>
            @endif

            <!-- STATS 2x2 -->
            <div class="stat-grid">

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5" style="display:flex;align-items:center;gap:12px;">
                        <div style="flex-shrink:0;background:#3b82f6;border-radius:8px;padding:12px;">
                            <svg style="width:24px;height:24px;color:white;" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:14px;color:#6b7280;font-weight:500;">Total Peminjaman</p>
                            <p style="font-size:28px;font-weight:700;color:#111827;">{{ $peminjaman->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5" style="display:flex;align-items:center;gap:12px;">
                        <div style="flex-shrink:0;background:#eab308;border-radius:8px;padding:12px;">
                            <svg style="width:24px;height:24px;" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:14px;color:#6b7280;font-weight:500;">Menunggu</p>
                            <p style="font-size:28px;font-weight:700;color:#111827;">{{ $peminjaman->getCollection()->where('status','menunggu')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5" style="display:flex;align-items:center;gap:12px;">
                        <div style="flex-shrink:0;background:#22c55e;border-radius:8px;padding:12px;">
                            <svg style="width:24px;height:24px;" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:14px;color:#6b7280;font-weight:500;">Sedang Dipinjam</p>
                            <p style="font-size:28px;font-weight:700;color:#111827;">{{ $peminjaman->getCollection()->where('status','dipinjam')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5" style="display:flex;align-items:center;gap:12px;">
                        <div style="flex-shrink:0;background:#ef4444;border-radius:8px;padding:12px;">
                            <svg style="width:24px;height:24px;" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:14px;color:#6b7280;font-weight:500;">Ditolak</p>
                            <p style="font-size:28px;font-weight:700;color:#111827;">{{ $peminjaman->getCollection()->where('status','ditolak')->count() }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tabel -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tgl Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tgl Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($peminjaman as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $peminjaman->firstItem() + $index }}</td>
                                <td class="px-6 py-4">
                                    @if($item->items->isNotEmpty())
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ optional($item->items->first()->alat)->nama ?? 'N/A' }}
                                        </div>
                                        @if($item->items->count() > 1)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">
                                            +{{ $item->items->count() - 1 }} alat lainnya
                                        </span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->items->sum('jumlah') }} unit</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badges = [
                                            'menunggu'               => ['yellow','Menunggu'],
                                            'disetujui'              => ['blue',  'Disetujui'],
                                            'dipinjam'               => ['green', 'Dipinjam'],
                                            'pengajuan_pengembalian' => ['purple','Pengajuan Kembali'],
                                            'di_denda'               => ['red',   'Di Denda'],
                                            'dikembalikan'           => ['gray',  'Dikembalikan'],
                                            'ditolak'                => ['red',   'Ditolak'],
                                            'dibatalkan'             => ['gray',  'Dibatalkan'],
                                        ];
                                        [$bc,$bl] = $badges[$item->status] ?? ['gray', ucfirst($item->status)];
                                    @endphp
                                    <span class="inline-flex rounded-full bg-{{ $bc }}-100 px-2 py-1 text-xs font-semibold text-{{ $bc }}-800">
                                        {{ $bl }}
                                    </span>
                                    @if($item->denda > 0)
                                    <div class="mt-1 text-xs font-medium text-red-600">
                                        Denda: Rp {{ number_format($item->denda, 0, ',', '.') }}
                                    </div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('peminjaman.show', $item->id) }}" class="text-blue-600 hover:text-blue-900">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peminjaman</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat peminjaman baru.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('peminjaman.create') }}"
                                           class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                            + Buat Peminjaman Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($peminjaman->hasPages())
                <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    {{ $peminjaman->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>