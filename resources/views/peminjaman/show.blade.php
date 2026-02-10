<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Peminjaman #{{ $peminjaman->id }}
            </h2>
            <a href="{{ route('peminjaman.index') }}"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            
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

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Status Badge -->
                    <div class="mb-6">
                        @switch($peminjaman->status)
                            @case('menunggu')
                            @case('pending')
                                <div class="rounded-lg border-l-4 border-yellow-500 bg-yellow-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Status: Menunggu Persetujuan</h3>
                                            <p class="mt-1 text-sm text-yellow-700">Pengajuan Anda sedang ditinjau oleh petugas. Harap tunggu maksimal 1x24 jam.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            
                            @case('disetujui')
                                <div class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">Status: Disetujui</h3>
                                            <p class="mt-1 text-sm text-blue-700">Pengajuan Anda telah disetujui. Silakan ambil alat ke petugas sesuai jadwal.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            
                            @case('dipinjam')
                                <div class="rounded-lg border-l-4 border-green-500 bg-green-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-green-800">Status: Sedang Dipinjam</h3>
                                            <p class="mt-1 text-sm text-green-700">Alat sedang Anda pinjam. Jangan lupa kembalikan sesuai jadwal atau ajukan pengembalian di bawah.</p>
                                        </div>
                                    </div>
                                </div>
                                @break

                            @case('pengajuan_pengembalian')
                                <div class="rounded-lg border-l-4 border-purple-500 bg-purple-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-purple-800">Status: Pengajuan Pengembalian</h3>
                                            <p class="mt-1 text-sm text-purple-700">Anda telah mengajukan pengembalian. Silakan datang ke petugas untuk mengembalikan alat. Petugas akan memeriksa kondisi alat.</p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            
                            @case('dikembalikan')
                                <div class="rounded-lg border-l-4 border-gray-500 bg-gray-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-800">Status: Sudah Dikembalikan</h3>
                                            <p class="mt-1 text-sm text-gray-700">
                                                Alat telah dikembalikan pada {{ $peminjaman->tanggal_kembali_actual?->format('d M Y H:i') ?? '-' }}
                                                @if($peminjaman->kondisi_alat)
                                                    dengan kondisi: <strong>{{ ucfirst($peminjaman->kondisi_alat) }}</strong>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @break
                            
                            @case('ditolak')
                                <div class="rounded-lg border-l-4 border-red-500 bg-red-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Status: Ditolak</h3>
                                            <p class="mt-1 text-sm text-red-700">Pengajuan Anda ditolak. Silakan hubungi petugas untuk informasi lebih lanjut.</p>
                                            @if($peminjaman->alasan_penolakan)
                                                <p class="mt-2 text-sm text-red-800"><strong>Alasan:</strong> {{ $peminjaman->alasan_penolakan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @break
                        @endswitch
                    </div>

                    <!-- Informasi Alat -->
                    <div class="mb-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Informasi Alat</h3>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Alat</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $peminjaman->alat->nama ?? $peminjaman->alat->nama_alat ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->alat->kategori ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Dipinjam</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->jumlah }} unit</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status Alat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($peminjaman->alat->status ?? '-') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Informasi Peminjaman -->
                    <div class="mb-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Informasi Peminjaman</h3>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Rencana Kembali</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d M Y') : '-' }}
                                    </dd>
                                </div>
                                @if($peminjaman->tanggal_kembali_actual)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Kembali Aktual</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_kembali_actual->format('d M Y H:i') }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Durasi Peminjaman</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $peminjaman->tanggal_pinjam && $peminjaman->tanggal_kembali ? $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) : 0 }} hari
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Surat Peminjaman -->
                    @if($peminjaman->surat_peminjaman)
                    <div class="mb-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Surat Peminjaman</h3>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <a href="{{ Storage::url($peminjaman->surat_peminjaman) }}" 
                                target="_blank"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lihat/Download Surat Peminjaman
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Catatan -->
                    @if($peminjaman->catatan)
                    <div class="mb-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Catatan</h3>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <p class="text-sm text-gray-900">{{ $peminjaman->catatan }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Catatan Petugas (jika ada) -->
                    @if($peminjaman->catatan_petugas)
                    <div class="mb-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Catatan Petugas</h3>
                        <div class="rounded-lg border border-gray-200 bg-yellow-50 p-4">
                            <p class="text-sm text-gray-900">{{ $peminjaman->catatan_petugas }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Tombol Ajukan Pengembalian -->
                    @if($peminjaman->status === 'dipinjam')
                    <div class="mb-6 border-t border-gray-200 pt-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Pengembalian Alat</h3>
                        <p class="mb-4 text-sm text-gray-600">
                            Jika Anda sudah selesai menggunakan alat, silakan ajukan pengembalian. Petugas akan memproses pengembalian Anda.
                        </p>
                        
                        <form action="{{ route('peminjaman.ajukan-pengembalian', $peminjaman) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin mengajukan pengembalian alat ini? Pastikan Anda sudah siap untuk mengembalikan alat ke petugas.')">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 font-semibold text-white transition-all hover:shadow-lg transform hover:scale-105">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                Ajukan Pengembalian
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Timeline/History -->
                    <div>
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Riwayat</h3>
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <!-- Step 1: Peminjaman Diajukan -->
                                <li>
                                    <div class="relative pb-8">
                                        @if($peminjaman->status !== 'menunggu' && $peminjaman->status !== 'pending')
                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900">Peminjaman diajukan</p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    {{ $peminjaman->created_at->format('d M Y, H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                <!-- Step 2: Disetujui/Ditolak -->
                                @if(!in_array($peminjaman->status, ['menunggu', 'pending']))
                                <li>
                                    <div class="relative pb-8">
                                        @if(!in_array($peminjaman->status, ['ditolak', 'disetujui']))
                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full {{ $peminjaman->status === 'ditolak' ? 'bg-red-500' : 'bg-green-500' }}">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        @if($peminjaman->status === 'ditolak')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        @else
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        @endif
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900">
                                                        {{ $peminjaman->status === 'ditolak' ? 'Peminjaman ditolak' : 'Peminjaman disetujui' }}
                                                    </p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    {{ $peminjaman->updated_at->format('d M Y, H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Step 3: Alat Diserahkan (Dipinjam) -->
                                @if(in_array($peminjaman->status, ['dipinjam', 'pengajuan_pengembalian', 'dikembalikan']))
                                <li>
                                    <div class="relative pb-8">
                                        @if(in_array($peminjaman->status, ['pengajuan_pengembalian', 'dikembalikan']))
                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900">Alat diserahkan</p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y, H:i') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Step 4: Pengajuan Pengembalian -->
                                @if(in_array($peminjaman->status, ['pengajuan_pengembalian', 'dikembalikan']))
                                <li>
                                    <div class="relative pb-8">
                                        @if($peminjaman->status === 'dikembalikan')
                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-500">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900">Pengajuan pengembalian</p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    {{ $peminjaman->updated_at->format('d M Y, H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Step 5: Alat Dikembalikan -->
                                @if($peminjaman->status === 'dikembalikan')
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-500">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900">Alat dikembalikan</p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    {{ $peminjaman->tanggal_kembali_actual?->format('d M Y, H:i') ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>