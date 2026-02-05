<x-admin-layout title="Import User">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Import User dari File</h1>
            <p class="text-gray-600">Impor data user dalam jumlah besar menggunakan file CSV atau Excel</p>
        </div>

        <!-- Instructions Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="font-semibold text-blue-900 mb-4">📋 Format File yang Diperlukan</h3>
            <p class="text-blue-800 mb-4">File harus berformat CSV atau Excel dengan kolom berikut (dalam urutan):</p>
            <div class="bg-white rounded p-4 mb-4">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left px-4 py-2">No</th>
                            <th class="text-left px-4 py-2">Nama Kolom</th>
                            <th class="text-left px-4 py-2">Keterangan</th>
                            <th class="text-left px-4 py-2">Contoh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="px-4 py-2">1</td>
                            <td class="px-4 py-2 font-medium">Nama</td>
                            <td class="px-4 py-2">Nama lengkap user</td>
                            <td class="px-4 py-2">John Doe</td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-4 py-2">2</td>
                            <td class="px-4 py-2 font-medium">Email</td>
                            <td class="px-4 py-2">Email unik</td>
                            <td class="px-4 py-2">john@example.com</td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-4 py-2">3</td>
                            <td class="px-4 py-2 font-medium">Password</td>
                            <td class="px-4 py-2">Password (minimal 8 karakter)</td>
                            <td class="px-4 py-2">Password123</td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-4 py-2">4</td>
                            <td class="px-4 py-2 font-medium">Role</td>
                            <td class="px-4 py-2">user, petugas, atau admin (opsional, default: user)</td>
                            <td class="px-4 py-2">petugas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-blue-800 text-sm">
                <strong>Catatan:</strong> Baris pertama file harus berisi header/nama kolom (akan diabaikan saat import)
            </p>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <!-- File Input -->
                <div>
                    <label for="file" class="block text-sm font-semibold text-gray-800 mb-2">
                        Pilih File (CSV atau Excel)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors cursor-pointer"
                        onclick="document.getElementById('file').click()">
                        <input type="file" id="file" name="file" accept=".csv,.xlsx,.xls,.txt" required
                            class="hidden" />
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                        <p class="text-gray-600 mb-2">Drag dan drop file di sini atau klik untuk memilih</p>
                        <p class="text-sm text-gray-500">Format: CSV, Excel (.xlsx, .xls)</p>
                    </div>
                    <p id="filename" class="mt-2 text-sm text-gray-600"></p>
                    @error('file')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                        Import Data
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>

            <!-- Download Template -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="font-semibold text-gray-800 mb-4">📥 Download Template</h3>
                <p class="text-gray-600 mb-4">Gunakan template ini untuk mempermudah import data:</p>
                <a href="{{ route('admin.users.template') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Template CSV
                </a>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('file');
        const filename = document.getElementById('filename');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                filename.textContent = '✓ File dipilih: ' + this.files[0].name;
            }
        });

        // Drag and drop
        const dropZone = fileInput.parentElement;
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            fileInput.files = e.dataTransfer.files;
            if (fileInput.files.length > 0) {
                filename.textContent = '✓ File dipilih: ' + fileInput.files[0].name;
            }
        });
    </script>
</x-admin-layout>
