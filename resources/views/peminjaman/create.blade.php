<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Form Peminjaman Alat
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Progress Indicator (Langkah 1, 2, 3) -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <!-- Step 1: Pilih Alat -->
                            <div class="flex items-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 font-bold text-white">1</div>
                                <span class="ml-2 text-sm font-medium text-gray-700">Pilih Alat</span>
                            </div>
                            <div class="mx-4 h-1 flex-1 bg-gray-300"></div>
                            
                            <!-- Step 2: Detail Peminjaman -->
                            <div class="flex items-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 font-bold text-white">2</div>
                                <span class="ml-2 text-sm font-medium text-gray-500">Detail Peminjaman</span>
                            </div>
                            <div class="mx-4 h-1 flex-1 bg-gray-300"></div>
                            
                            <!-- Step 3: Konfirmasi -->
                            <div class="flex items-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 font-bold text-white">3</div>
                                <span class="ml-2 text-sm font-medium text-gray-500">Konfirmasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tampilkan Error jika ada -->
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

                    <!-- FORM PEMINJAMAN -->
                    <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data" id="formPeminjaman">
                        @csrf

                        <!-- 1. PILIH ALAT -->
                        <div class="mb-6">
                            <label for="tool_id" class="mb-2 block text-sm font-medium text-gray-700">
                                1. Pilih Alat yang Ingin Dipinjam <span class="text-red-500">*</span>
                            </label>
                            <select name="tool_id" id="tool_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Alat --</option>
                                @foreach($alat as $item)
                                    <option value="{{ $item->id }}" 
                                            data-stok="{{ $item->jumlah }}"
                                            {{ old('tool_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }} (Stok Tersedia: {{ $item->jumlah }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Pilih alat dari daftar yang tersedia</p>
                        </div>

                        <!-- 2. JUMLAH BARANG -->
                        <div class="mb-6">
                            <label for="jumlah" class="mb-2 block text-sm font-medium text-gray-700">
                                2. Jumlah Barang yang Dipinjam <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah" id="jumlah" min="1" required
                                value="{{ old('jumlah', 1) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500" id="info-stok">Masukkan jumlah yang ingin dipinjam</p>
                        </div>

                        <!-- 3. TANGGAL PINJAM -->
                        <div class="mb-6">
                            <label for="tanggal_pinjam" class="mb-2 block text-sm font-medium text-gray-700">
                                3. Tanggal Mulai Peminjaman <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required
                                value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Pilih tanggal mulai peminjaman (minimal hari ini)</p>
                        </div>

                        <!-- 4. TANGGAL PENGEMBALIAN -->
                        <div class="mb-6">
                            <label for="tanggal_kembali" class="mb-2 block text-sm font-medium text-gray-700">
                                4. Tanggal Rencana Pengembalian <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" required
                                value="{{ old('tanggal_kembali') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Pilih tanggal rencana pengembalian alat</p>
                        </div>

                        <!-- 5. SURAT PEMINJAMAN (OPSIONAL) -->
                        <div class="mb-6">
                            <label for="surat_peminjaman" class="mb-2 block text-sm font-medium text-gray-700">
                                5. Surat Peminjaman (Opsional)
                            </label>
                            <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pb-6 pt-5 transition-colors hover:border-blue-400">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="surat_peminjaman" class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none hover:text-blue-500">
                                            <span>Unggah file</span>
                                            <input id="surat_peminjaman" name="surat_peminjaman" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">atau seret dan lepas di sini</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, JPG, PNG hingga 2MB</p>
                                </div>
                            </div>
                            <div id="nama-file" class="mt-2 text-sm text-gray-600"></div>
                            <p class="mt-1 text-sm text-gray-500">Unggah surat peminjaman jika diperlukan oleh institusi Anda</p>
                        </div>

                        <!-- 6. CATATAN TAMBAHAN (OPSIONAL) -->
                        <div class="mb-6">
                            <label for="catatan" class="mb-2 block text-sm font-medium text-gray-700">
                                6. Catatan Tambahan (Opsional)
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: Alat akan digunakan untuk praktikum di Lab Komputer">{{ old('catatan') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Tambahkan informasi tambahan jika diperlukan</p>
                        </div>

                        <!-- INFORMASI PENTING -->
                        <div class="mb-6 rounded-md bg-blue-50 p-4">
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
                                            <li>Pengajuan akan diproses oleh admin maksimal 1x24 jam</li>
                                            <li>Pastikan tanggal pengembalian sesuai dengan kebutuhan Anda</li>
                                            <li>Alat yang rusak/hilang akan dikenakan sanksi sesuai ketentuan</li>
                                            <li>Hubungi admin jika ada kendala: admin@example.com</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('dashboard') }}"
                                class="rounded-md border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="transform rounded-md bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-2 text-sm font-medium text-white transition-all hover:scale-105 hover:shadow-lg">
                                Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // JAVASCRIPT: Validasi dan Helper

        // 1. Validasi Stok Alat
        document.getElementById('tool_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stok = selectedOption.dataset.stok;
            const inputJumlah = document.getElementById('jumlah');
            const infoStok = document.getElementById('info-stok');
            
            if (stok) {
                inputJumlah.max = stok;
                infoStok.textContent = `Stok tersedia: ${stok} unit`;
                infoStok.classList.remove('text-gray-500');
                infoStok.classList.add('text-green-600', 'font-medium');
            }
        });

        // 2. Validasi Tanggal Pengembalian (harus setelah tanggal pinjam)
        document.getElementById('tanggal_pinjam').addEventListener('change', function() {
            const tanggalPinjam = new Date(this.value);
            const inputTanggalKembali = document.getElementById('tanggal_kembali');
            
            // Set minimal tanggal kembali = tanggal pinjam + 1 hari
            tanggalPinjam.setDate(tanggalPinjam.getDate() + 1);
            inputTanggalKembali.min = tanggalPinjam.toISOString().split('T')[0];
        });

        // 3. Preview Nama File yang Diupload
        document.getElementById('surat_peminjaman').addEventListener('change', function() {
            const namaFile = this.files[0]?.name;
            const tampilNamaFile = document.getElementById('nama-file');
            
            if (namaFile) {
                tampilNamaFile.textContent = `📄 File terpilih: ${namaFile}`;
                tampilNamaFile.classList.add('text-blue-600', 'font-medium');
            } else {
                tampilNamaFile.textContent = '';
                tampilNamaFile.classList.remove('text-blue-600', 'font-medium');
            }
        });

        // 4. Validasi Jumlah tidak melebihi stok
        document.getElementById('jumlah').addEventListener('input', function() {
            const max = parseInt(this.max);
            const value = parseInt(this.value);
            const infoStok = document.getElementById('info-stok');
            
            if (value > max && max > 0) {
                this.value = max;
                infoStok.textContent = `Jumlah maksimal: ${max} unit`;
                infoStok.classList.remove('text-green-600');
                infoStok.classList.add('text-red-600', 'font-medium');
            }
        });
    </script>
    @endpush
</x-app-layout>