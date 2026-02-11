<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detail Peminjaman #{{ $peminjaman->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="mb-6 border-l-4 border-green-500 bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 border-l-4 border-yellow-500 bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 border-l-4 border-red-500 bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 border-l-4 border-red-500 bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                            <ul class="mt-2 list-inside list-disc text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card Detail Peminjaman -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Peminjaman</h3>
                        
                        <!-- Status Badge -->
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                            @if($peminjaman->status === 'menunggu') bg-yellow-100 text-yellow-800
                            @elseif($peminjaman->status === 'disetujui') bg-blue-100 text-blue-800
                            @elseif($peminjaman->status === 'dipinjam') bg-purple-100 text-purple-800
                            @elseif($peminjaman->status === 'pengajuan_pengembalian') bg-orange-100 text-orange-800
                            @elseif($peminjaman->status === 'dikembalikan') bg-green-100 text-green-800
                            @elseif($peminjaman->status === 'ditolak') bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                        </span>
                    </div>

                    <!-- Detail Data -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Alat</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->alat->nama ?? 'Alat tidak ditemukan' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Jumlah</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->jumlah }} unit</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Pinjam</p>
                                <p class="mt-1 text-base text-gray-900">
                                    {{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Rencana Kembali</p>
                                <p class="mt-1 text-base text-gray-900">
                                    {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') : '-' }}
                                </p>
                            </div>
                            @if($peminjaman->tanggal_kembali_actual)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Kembali Aktual</p>
                                <p class="mt-1 text-base text-gray-900">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_actual)->format('d F Y') }}
                                </p>
                            </div>
                            @endif
                            @if($peminjaman->denda > 0)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Denda</p>
                                <p class="mt-1 text-base font-semibold text-red-600">
                                    Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                </p>
                                @if($peminjaman->jumlah_hari_terlambat > 0)
                                <p class="mt-1 text-xs text-red-500">Terlambat {{ $peminjaman->jumlah_hari_terlambat }} hari</p>
                                @endif
                            </div>
                            @endif
                        </div>

                        @if($peminjaman->catatan)
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Catatan</p>
                            <p class="mt-1 text-base text-gray-900">{{ $peminjaman->catatan }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Form Ajukan Pengembalian (hanya untuk status = dipinjam) -->
                    @if($peminjaman->status === 'dipinjam')
                    <div class="mt-8 border-t pt-6">
                        <h4 class="mb-4 text-md font-semibold text-gray-900">Ajukan Pengembalian Alat</h4>
                        <form action="{{ route('peminjaman.ajukan-pengembalian', $peminjaman) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="formPengembalian">
                            @csrf
                            
                            <div>
                                <label for="tanggal_kembali_actual" class="block text-sm font-medium text-gray-700">
                                    Tanggal Pengembalian Aktual <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_kembali_actual" id="tanggal_kembali_actual" 
                                    value="{{ old('tanggal_kembali_actual', date('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Pilih tanggal Anda mengembalikan alat (minimal hari ini)</p>
                            </div>

                            <div>
                                <label for="foto_pengembalian" class="block text-sm font-medium text-gray-700">
                                    Foto Dokumentasi Pengembalian <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="foto_pengembalian" id="foto_pengembalian" 
                                    accept="image/jpeg,image/png,image/jpg" required
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                                <div id="preview-foto" class="mt-2"></div>
                            </div>

                            <!-- Info Box -->
                            <div class="rounded-md bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Informasi Penting:</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-inside list-disc space-y-1">
                                                <li>Tanggal pengembalian minimal hari ini, tidak bisa memilih tanggal kemarin</li>
                                                <li>Pastikan foto dokumentasi menunjukkan kondisi alat dengan jelas</li>
                                                <li>Jika terlambat mengembalikan, akan dikenakan denda sesuai ketentuan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" 
                                class="w-full transform rounded-md bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 text-sm font-medium text-white transition-all hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Ajukan Pengembalian
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Form Upload Bukti Pembayaran Denda -->
                    @if($peminjaman->denda > 0 && in_array($peminjaman->status_pembayaran_denda, ['belum_bayar', null]))
                    <div class="mt-8 border-t pt-6">
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <p class="text-sm font-medium text-red-800">
                                Anda memiliki denda sebesar: <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong>
                            </p>
                            @if($peminjaman->jumlah_hari_terlambat > 0)
                            <p class="mt-1 text-xs text-red-700">Terlambat {{ $peminjaman->jumlah_hari_terlambat }} hari</p>
                            @endif
                        </div>

                        <h4 class="mb-4 text-md font-semibold text-gray-900">Upload Bukti Pembayaran Denda</h4>
                        <form action="{{ route('peminjaman.upload-bukti-pembayaran', $peminjaman) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700">
                                    Bukti Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" 
                                    accept="image/jpeg,image/png,image/jpg" required
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-green-700 hover:file:bg-green-100">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                                <div id="preview-bukti" class="mt-2"></div>
                            </div>

                            <button type="submit" 
                                class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Upload Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                    @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                    <div class="mt-8 border-t pt-6">
                        <div class="rounded-md bg-yellow-50 p-4">
                            <p class="text-sm font-medium text-yellow-800">
                                ⏳ Bukti pembayaran Anda sedang diverifikasi oleh petugas
                            </p>
                        </div>
                    </div>
                    @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                    <div class="mt-8 border-t pt-6">
                        <div class="rounded-md bg-green-50 p-4">
                            <p class="text-sm font-medium text-green-800">
                                ✓ Pembayaran denda telah diverifikasi
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Tombol Kembali -->
                    <div class="mt-8 border-t pt-6">
                        <a href="{{ route('peminjaman.index') }}" 
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50">
                            ← Kembali ke Daftar Peminjaman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // 1. Preview foto pengembalian
        document.getElementById('foto_pengembalian')?.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('preview-foto');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-2">Preview:</p>
                            <img src="${e.target.result}" class="max-w-xs rounded-md border" alt="Preview">
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // 2. Preview bukti pembayaran
        document.getElementById('bukti_pembayaran')?.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('preview-bukti');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-2">Preview:</p>
                            <img src="${e.target.result}" class="max-w-xs rounded-md border" alt="Preview">
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });
    </script>
    @endpush
</x-app-layout>