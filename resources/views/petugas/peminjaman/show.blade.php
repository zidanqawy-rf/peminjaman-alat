<x-petugas-layout>
    <x-slot name="title">Detail Peminjaman</x-slot>
    <x-slot name="header">Detail Peminjaman #{{ $peminjaman->id }}</x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('petugas.peminjaman.index') }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="mb-6 rounded-md border-l-4 border-green-500 bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-md border-l-4 border-red-500 bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <!-- Header -->
                        <div class="border-b border-gray-200 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white">Informasi Peminjaman</h3>
                            <p class="text-sm text-emerald-100">ID: #{{ $peminjaman->id }}</p>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Data Peminjam -->
                            <div>
                                <h4 class="mb-3 flex items-center text-lg font-semibold text-gray-800">
                                    <svg class="mr-2 h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Data Peminjam
                                </h4>
                                <div class="rounded-lg bg-gray-50 p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nama:</span>
                                        <span class="font-semibold text-gray-800">{{ $peminjaman->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="font-semibold text-gray-800">{{ $peminjaman->user->email }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">No. HP:</span>
                                        <span class="font-semibold text-gray-800">{{ $peminjaman->user->no_hp ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Alat -->
                            <div>
                                <h4 class="mb-3 flex items-center text-lg font-semibold text-gray-800">
                                    <svg class="mr-2 h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Data Alat
                                </h4>
                                <div class="rounded-lg bg-gray-50 p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nama Alat:</span>
                                        <span class="font-semibold text-gray-800">{{ $peminjaman->alat->nama_alat ?? $peminjaman->alat->nama ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Kategori:</span>
                                        <span class="font-semibold text-gray-800">{{ $peminjaman->alat->kategori ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Jumlah Dipinjam:</span>
                                        <span class="font-semibold text-emerald-600">{{ $peminjaman->jumlah }} unit</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Stok Saat Ini:</span>
                                        <span class="font-semibold text-gray-800">{{ $peminjaman->alat->jumlah ?? 0 }} unit</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Waktu Peminjaman -->
                            <div>
                                <h4 class="mb-3 flex items-center text-lg font-semibold text-gray-800">
                                    <svg class="mr-2 h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Waktu Peminjaman
                                </h4>
                                <div class="rounded-lg bg-gray-50 p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Pinjam:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Kembali:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                    @if ($peminjaman->tanggal_kembali_actual)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kembali Actual:</span>
                                            <span class="font-semibold text-green-600">
                                                {{ $peminjaman->tanggal_kembali_actual->format('d M Y H:i') }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Durasi:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ $peminjaman->tanggal_pinjam && $peminjaman->tanggal_kembali ? $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) : 0 }} hari
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan User -->
                            @if($peminjaman->catatan)
                                <div>
                                    <h4 class="mb-3 flex items-center text-lg font-semibold text-gray-800">
                                        <svg class="mr-2 h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Catatan Peminjam
                                    </h4>
                                    <div class="rounded-lg bg-gray-50 p-4">
                                        <p class="text-gray-700">{{ $peminjaman->catatan }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Kondisi Alat (jika sudah dikembalikan) -->
                            @if($peminjaman->kondisi_alat)
                                <div>
                                    <h4 class="mb-3 flex items-center text-lg font-semibold text-gray-800">
                                        <svg class="mr-2 h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Kondisi Alat
                                    </h4>
                                    <div class="rounded-lg p-4 {{ $peminjaman->kondisi_alat === 'baik' ? 'bg-green-50' : ($peminjaman->kondisi_alat === 'rusak' ? 'bg-yellow-50' : 'bg-red-50') }}">
                                        <p class="font-semibold {{ $peminjaman->kondisi_alat === 'baik' ? 'text-green-800' : ($peminjaman->kondisi_alat === 'rusak' ? 'text-yellow-800' : 'text-red-800') }}">
                                            {{ ucfirst($peminjaman->kondisi_alat) }}
                                        </p>
                                        @if($peminjaman->catatan_petugas)
                                            <p class="mt-2 text-sm text-gray-700">{{ $peminjaman->catatan_petugas }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Status & Actions -->
                <div class="lg:col-span-1">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg sticky top-6">
                        <!-- Status Badge -->
                        <div class="border-b border-gray-200 p-6">
                            <h4 class="mb-3 text-sm font-semibold text-gray-600">STATUS PEMINJAMAN</h4>
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu'],
                                    'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu'],
                                    'disetujui' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Disetujui'],
                                    'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                    'dipinjam' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Dipinjam'],
                                    'pengajuan_pengembalian' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Pengajuan Pengembalian'],
                                    'dikembalikan' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Dikembalikan'],
                                    'selesai' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Selesai'],
                                ];
                                $config = $statusConfig[$peminjaman->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($peminjaman->status)];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                <span class="mr-2 h-2 w-2 rounded-full {{ str_replace('100', '500', $config['bg']) }}"></span>
                                {{ $config['label'] }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="p-6 space-y-3">
                            {{-- MENUNGGU: Approve atau Reject --}}
                            @if (in_array($peminjaman->status, ['pending', 'menunggu']))
                                <!-- Approve Button -->
                                <form action="{{ route('petugas.peminjaman.approve', $peminjaman) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')"
                                        class="flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 font-semibold text-white transition-all hover:shadow-lg transform hover:scale-105">
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Setujui Peminjaman
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <button onclick="openRejectModal()"
                                    class="flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-4 py-3 font-semibold text-white transition-all hover:shadow-lg transform hover:scale-105">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak Peminjaman
                                </button>
                            @endif

                            {{-- DISETUJUI: Serahkan Alat --}}
                            @if ($peminjaman->status === 'disetujui')
                                <form action="{{ route('petugas.peminjaman.serahkan', $peminjaman) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi bahwa alat sudah diserahkan kepada peminjam?\n\nStok alat akan dikurangi sebanyak {{ $peminjaman->jumlah }} unit.')"
                                        class="flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 px-4 py-3 font-semibold text-white transition-all hover:shadow-lg transform hover:scale-105">
                                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Serahkan Alat
                                    </button>
                                </form>
                            @endif

                            {{-- PENGAJUAN PENGEMBALIAN: Terima Pengembalian --}}
                            @if ($peminjaman->status === 'pengajuan_pengembalian')
                                <button onclick="openReturnModal()"
                                    class="flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 font-semibold text-white transition-all hover:shadow-lg transform hover:scale-105">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Terima Pengembalian
                                </button>
                            @endif

                            {{-- DITOLAK: Tampilkan Alasan --}}
                            @if ($peminjaman->status === 'ditolak' && $peminjaman->alasan_penolakan)
                                <div class="mt-6 border-t border-gray-200 pt-6">
                                    <h5 class="mb-2 text-xs font-semibold text-gray-600">ALASAN PENOLAKAN</h5>
                                    <p class="rounded-lg bg-red-50 p-3 text-sm text-red-600">
                                        {{ $peminjaman->alasan_penolakan }}
                                    </p>
                                </div>
                            @endif

                            <!-- Timeline -->
                            <div class="mt-6 border-t border-gray-200 pt-6">
                                <h5 class="mb-3 text-xs font-semibold text-gray-600">TIMELINE</h5>
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-start">
                                        <div class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full bg-green-500"></div>
                                        <div class="ml-3">
                                            <p class="text-gray-600">Diajukan</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $peminjaman->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    @if (!in_array($peminjaman->status, ['pending', 'menunggu']))
                                        <div class="flex items-start">
                                            <div class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full {{ $peminjaman->status === 'ditolak' ? 'bg-red-500' : 'bg-green-500' }}"></div>
                                            <div class="ml-3">
                                                <p class="text-gray-600">
                                                    {{ $peminjaman->status === 'ditolak' ? 'Ditolak' : 'Disetujui' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $peminjaman->updated_at->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if (in_array($peminjaman->status, ['dipinjam', 'pengajuan_pengembalian', 'dikembalikan', 'selesai']))
                                        <div class="flex items-start">
                                            <div class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full bg-green-500"></div>
                                            <div class="ml-3">
                                                <p class="text-gray-600">Diserahkan</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if (in_array($peminjaman->status, ['pengajuan_pengembalian', 'dikembalikan', 'selesai']))
                                        <div class="flex items-start">
                                            <div class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full bg-purple-500"></div>
                                            <div class="ml-3">
                                                <p class="text-gray-600">Pengajuan Pengembalian</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $peminjaman->updated_at->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if (in_array($peminjaman->status, ['dikembalikan', 'selesai']))
                                        <div class="flex items-start">
                                            <div class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full bg-green-500"></div>
                                            <div class="ml-3">
                                                <p class="text-gray-600">Dikembalikan</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $peminjaman->tanggal_kembali_actual ? $peminjaman->tanggal_kembali_actual->format('d M Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-6">
            <h3 class="mb-4 text-xl font-bold text-gray-800">Tolak Peminjaman</h3>
            <form action="{{ route('petugas.peminjaman.reject', $peminjaman) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Alasan Penolakan (Opsional)
                    </label>
                    <textarea name="alasan_penolakan" rows="4"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Masukkan alasan penolakan..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 rounded-lg bg-gray-200 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-white transition-colors hover:bg-red-700">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Terima Pengembalian -->
    <div id="returnModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-6">
            <h3 class="mb-4 text-xl font-bold text-gray-800">Terima Pengembalian Alat</h3>
            <form action="{{ route('petugas.peminjaman.terima-kembali', $peminjaman) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Kondisi Alat <span class="text-red-500">*</span>
                    </label>
                    <select name="kondisi_alat" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">Baik - Tidak ada kerusakan</option>
                        <option value="rusak">Rusak - Ada kerusakan</option>
                        <option value="hilang">Hilang - Alat tidak dikembalikan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Catatan Petugas (Opsional)
                    </label>
                    <textarea name="catatan_petugas" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <div class="mb-4 rounded-lg bg-yellow-50 p-3">
                    <p class="text-sm text-yellow-800">
                        <strong>Perhatian:</strong> Stok alat akan dikembalikan sebanyak <strong>{{ $peminjaman->jumlah }} unit</strong> 
                        (kecuali jika kondisi "Hilang").
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="closeReturnModal()"
                        class="flex-1 rounded-lg bg-gray-200 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700">
                        Proses Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

        function openReturnModal() {
            document.getElementById('returnModal').classList.remove('hidden');
            document.getElementById('returnModal').classList.add('flex');
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.add('hidden');
            document.getElementById('returnModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        document.getElementById('returnModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReturnModal();
            }
        });
    </script>
</x-petugas-layout>