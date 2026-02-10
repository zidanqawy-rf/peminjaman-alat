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
            
            {{-- Alert Messages --}}
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

            @if (session('warning'))
                <div class="mb-6 rounded-md border-l-4 border-yellow-500 bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
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

            {{-- Status Badge (tetap sama seperti sebelumnya) --}}
            {{-- ... --}}

            {{-- DENDA & PEMBAYARAN --}}
            @if($peminjaman->denda > 0)
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-l-4 {{ $peminjaman->status_pembayaran_denda === 'terverifikasi' ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }} p-6">
                    <h3 class="mb-4 text-lg font-bold {{ $peminjaman->status_pembayaran_denda === 'terverifikasi' ? 'text-green-900' : 'text-red-900' }}">
                        ⚠️ Informasi Denda Keterlambatan
                    </h3>
                    
                    <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm text-gray-600">Terlambat</p>
                            <p class="text-2xl font-bold text-red-600">{{ $peminjaman->jumlah_hari_terlambat }} Hari</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm text-gray-600">Denda Per Hari</p>
                            <p class="text-2xl font-bold text-gray-800">Rp 5.000</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm text-gray-600">Total Denda</p>
                            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Status Pembayaran --}}
                    <div class="mb-4">
                        <p class="mb-2 text-sm font-semibold text-gray-700">Status Pembayaran:</p>
                        @if($peminjaman->status_pembayaran_denda === 'belum_bayar')
                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800">
                                <span class="mr-2 h-2 w-2 rounded-full bg-red-500"></span>
                                Belum Bayar
                            </span>
                        @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800">
                                <span class="mr-2 h-2 w-2 rounded-full bg-yellow-500"></span>
                                Menunggu Verifikasi Admin
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                <span class="mr-2 h-2 w-2 rounded-full bg-green-500"></span>
                                Terverifikasi
                            </span>
                        @endif
                    </div>

                    {{-- Informasi Pembayaran --}}
                    <div class="rounded-lg bg-blue-50 p-4 mb-4">
                        <h4 class="mb-2 font-semibold text-blue-900">📋 Informasi Pembayaran:</h4>
                        <div class="space-y-1 text-sm text-blue-800">
                            <p><strong>Bank:</strong> BCA</p>
                            <p><strong>No. Rekening:</strong> 1234567890</p>
                            <p><strong>Atas Nama:</strong> Lab Komputer XYZ</p>
                            <p class="mt-2 text-xs text-blue-600">* Silakan transfer sesuai nominal denda dan upload bukti pembayaran</p>
                        </div>
                    </div>

                    {{-- Form Upload Bukti (jika belum bayar atau ditolak) --}}
                    @if(in_array($peminjaman->status_pembayaran_denda, ['belum_bayar', 'menunggu_verifikasi']))
                        <form action="{{ route('peminjaman.upload-bukti-pembayaran', $peminjaman) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Upload Bukti Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="bukti_pembayaran" accept="image/*" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('bukti_pembayaran')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($peminjaman->bukti_pembayaran_denda)
                                <div class="mb-4">
                                    <p class="mb-2 text-sm font-semibold text-gray-700">Bukti Saat Ini:</p>
                                    <img src="{{ Storage::url($peminjaman->bukti_pembayaran_denda) }}" 
                                        alt="Bukti Pembayaran" 
                                        class="h-48 w-auto rounded-lg border border-gray-300 object-cover">
                                </div>
                            @endif

                            <button type="submit"
                                class="w-full rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 font-semibold text-white transition-all hover:shadow-lg transform hover:scale-105">
                                {{ $peminjaman->bukti_pembayaran_denda ? 'Update Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                            </button>
                        </form>
                    @endif

                    {{-- Catatan Admin (jika ada) --}}
                    @if($peminjaman->catatan_admin_pembayaran)
                        <div class="mt-4 rounded-lg bg-yellow-50 p-4">
                            <h5 class="mb-2 font-semibold text-yellow-900">📝 Catatan Admin:</h5>
                            <p class="text-sm text-yellow-800">{{ $peminjaman->catatan_admin_pembayaran }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Informasi Alat, Peminjaman, dst (tetap sama) --}}
            {{-- ... --}}

            {{-- Tombol Ajukan Pengembalian --}}
            @if($peminjaman->status === 'dipinjam')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-t border-gray-200 p-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">📦 Pengembalian Alat</h3>
                    <p class="mb-4 text-sm text-gray-600">
                        Lengkapi form di bawah untuk mengajukan pengembalian alat. Pastikan Anda memasukkan tanggal pengembalian dan foto dokumentasi kondisi alat.
                    </p>
                    
                    <form action="{{ route('peminjaman.ajukan-pengembalian', $peminjaman) }}" method="POST" enctype="multipart/form-data"
                        onsubmit="return confirm('Apakah data yang Anda masukkan sudah benar?')">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Tanggal Pengembalian <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_kembali_actual" required
                                value="{{ old('tanggal_kembali_actual', now()->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tanggal_kembali_actual')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Rencana kembali: {{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
                        </div>

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Foto Dokumentasi Pengembalian <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="foto_pengembalian" accept="image/*" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="previewImage(event)">
                            @error('foto_pengembalian')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                            
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="preview" class="h-48 w-auto rounded-lg border border-gray-300 object-cover" alt="Preview">
                            </div>
                        </div>

                        <div class="rounded-lg bg-yellow-50 p-4 mb-4">
                            <p class="text-sm text-yellow-800">
                                <strong>⚠️ Perhatian:</strong> Jika Anda terlambat mengembalikan, akan ada denda <strong>Rp 5.000/hari</strong>.
                            </p>
                        </div>

                        <button type="submit"
                            class="flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 font-semibold text-white transition-all hover:shadow-lg transform hover:scale-105">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Ajukan Pengembalian
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Foto Pengembalian (jika sudah diupload) --}}
            @if($peminjaman->foto_pengembalian)
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">📸 Foto Dokumentasi Pengembalian</h3>
                    <img src="{{ Storage::url($peminjaman->foto_pengembalian) }}" 
                        alt="Foto Pengembalian" 
                        class="h-64 w-auto rounded-lg border border-gray-300 object-cover">
                </div>
            </div>
            @endif

            {{-- Timeline (tetap sama) --}}
            {{-- ... --}}

        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>