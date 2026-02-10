<x-admin-layout title="Kelola Alat">
    <div x-data="{ 
        openAdd: false, 
        openEdit: false, 
        editAlatId: null, 
        editAlatData: null, 
        deleteAlatId: null, 
        openDelete: false,
        openImport: false,
        previewImage: null,
        editPreviewImage: null
    }">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Header with Add Button -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Alat</h1>
                <p class="text-gray-600">Tambah, edit, atau hapus data alat yang tersedia dalam inventaris</p>
            </div>
            <div class="flex gap-3">
                <!-- Tombol Kelola Kategori -->
                <a href="{{ route('admin.kategori.index') }}"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Kelola Kategori
                </a>

                <!-- Tombol Import Excel -->
                <button @click="openImport = true"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                        </path>
                    </svg>
                    Import Excel
                </button>

                <!-- Tombol Tambah Alat Baru -->
                <button @click="openAdd = true"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Alat Baru
                </button>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama, kategori, atau status..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Alat Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table id="alatsTable" class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Gambar</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama Alat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kategori</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Jumlah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alats as $index => $alat)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($alat->gambar)
                                        <img src="{{ asset('storage/' . $alat->gambar) }}" 
                                             alt="{{ $alat->nama }}"
                                             class="w-16 h-16 object-cover rounded-lg shadow">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <span class="font-medium">{{ $alat->nama }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $alat->kategori }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <span class="font-semibold">{{ $alat->jumlah }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($alat->status == 'tersedia')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Tersedia</span>
                                    @elseif ($alat->status == 'dipinjam')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Dipinjam</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Rusak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm flex items-center space-x-3">
                                    <button
                                        @click="editAlatId = {{ $alat->id }}; editAlatData = {nama: '{{ $alat->nama }}', kategori: '{{ $alat->kategori }}', jumlah: {{ $alat->jumlah }}, status: '{{ $alat->status }}', gambar: '{{ $alat->gambar ? asset('storage/' . $alat->gambar) : '' }}'}; openEdit = true"
                                        class="px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors inline-flex items-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        <span class="ml-1">Edit</span>
                                    </button>

                                    <button @click="deleteAlatId = {{ $alat->id }}; openDelete = true"
                                        class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors inline-flex items-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        <span class="ml-1">Hapus</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                        </path>
                                    </svg>
                                    <p>Belum ada data alat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div class="mt-4 px-6 py-3 bg-gray-50 border-t border-gray-200 text-sm text-gray-600">
                <span id="recordsInfo">Menampilkan data alat</span>
            </div>
        </div>

        <!-- Modal Tambah Alat -->
        <x-admin-modal id="Add" title="Tambah Alat Baru">
            <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Gambar Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 mb-1">Gambar Alat</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img x-show="previewImage" :src="previewImage" alt="Preview" 
                                class="w-24 h-24 object-cover rounded-lg border-2 border-gray-300">
                            <div x-show="!previewImage" 
                                class="w-24 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="gambar" id="gambar" accept="image/*"
                                @change="previewImage = URL.createObjectURL($event.target.files[0])"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
                        </div>
                    </div>
                    @error('gambar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Alat Field -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-800 mb-1">Nama Alat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                        value="{{ old('nama') }}" required />
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Field - DROPDOWN -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-800 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" id="kategori"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-500 @enderror"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->nama }}" {{ old('kategori') == $kategori->nama ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Kategori tidak ada? 
                        <a href="{{ route('admin.kategori.index') }}" target="_blank" class="text-blue-600 hover:underline">
                            Tambah kategori baru
                        </a>
                    </p>
                    @error('kategori')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah Field -->
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-800 mb-1">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jumlah') border-red-500 @enderror"
                        value="{{ old('jumlah', 0) }}" required />
                    @error('jumlah')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Field -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-800 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="rusak" {{ old('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Simpan
                    </button>
                    <button type="button" @click="openAdd = false; previewImage = null"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </x-admin-modal>

        <!-- Modal Edit Alat -->
        <x-admin-modal id="Edit" title="Edit Alat">
            <template x-if="editAlatId && editAlatData">
                <form :action="`/admin/alat/${editAlatId}`" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Gambar Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Gambar Alat</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img x-show="editPreviewImage || editAlatData?.gambar" 
                                     :src="editPreviewImage || editAlatData?.gambar" 
                                     alt="Preview" 
                                     class="w-24 h-24 object-cover rounded-lg border-2 border-gray-300">
                                <div x-show="!editPreviewImage && !editAlatData?.gambar" 
                                    class="w-24 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="gambar" accept="image/*"
                                    @change="editPreviewImage = URL.createObjectURL($event.target.files[0])"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nama Alat Field -->
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-800 mb-1">Nama Alat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :value="editAlatData?.nama || ''" required />
                    </div>

                    <!-- Kategori Field - DROPDOWN -->
                    <div>
                        <label for="edit_kategori" class="block text-sm font-medium text-gray-800 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" id="edit_kategori"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->nama }}" :selected="editAlatData?.kategori === '{{ $kategori->nama }}'">
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Kategori tidak ada? 
                            <a href="{{ route('admin.kategori.index') }}" target="_blank" class="text-blue-600 hover:underline">
                                Tambah kategori baru
                            </a>
                        </p>
                    </div>

                    <!-- Jumlah Field -->
                    <div>
                        <label for="edit_jumlah" class="block text-sm font-medium text-gray-800 mb-1">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" id="edit_jumlah" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :value="editAlatData?.jumlah || 0" required />
                    </div>

                    <!-- Status Field -->
                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-gray-800 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="edit_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="tersedia" :selected="editAlatData?.status === 'tersedia'">Tersedia</option>
                            <option value="dipinjam" :selected="editAlatData?.status === 'dipinjam'">Dipinjam</option>
                            <option value="rusak" :selected="editAlatData?.status === 'rusak'">Rusak</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="openEdit = false; editPreviewImage = null"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                            Batal
                        </button>
                    </div>
                </form>
            </template>
        </x-admin-modal>

        <!-- Modal Hapus Alat -->
        <div x-show="openDelete" @click="openDelete = false"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            style="display: none;">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-sm w-full">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Hapus Alat</h3>
                    <button @click="openDelete = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus alat ini? Tindakan ini tidak dapat diubah.</p>
                    <div class="flex items-center space-x-3">
                        <template x-if="deleteAlatId">
                            <form :action="`/admin/alat/${deleteAlatId}`" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                    Hapus
                                </button>
                            </form>
                        </template>
                        <button type="button" @click="openDelete = false"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Import Excel -->
        <div x-show="openImport" @click="openImport = false"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            style="display: none;">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Import Data Alat dari Excel</h3>
                    <button @click="openImport = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('admin.alat.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    <!-- Info -->
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Format File Excel:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Kolom: Nama, Kategori, Jumlah, Status</li>
                                    <li>Status: tersedia/dipinjam/rusak</li>
                                    <li>File format: .xlsx atau .xls</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Download Template -->
                    <div class="mb-4">
                        <a href="{{ route('admin.alat.template') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download Template Excel
                        </a>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            Pilih File Excel <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file" accept=".xlsx,.xls" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Format: .xlsx atau .xls (Max: 5MB)</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center space-x-3">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Import Data
                        </button>
                        <button type="button" @click="openImport = false"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Search functionality
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const tableRows = document.querySelectorAll('#alatsTable tbody tr');
                let visibleCount = 0;

                tableRows.forEach(row => {
                    const nama = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                    const kategori = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    const status = row.querySelector('td:nth-child(6)')?.textContent.toLowerCase() || '';

                    const matches = nama.includes(searchTerm) ||
                        kategori.includes(searchTerm) ||
                        status.includes(searchTerm);

                    if (matches) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const recordsInfo = document.getElementById('recordsInfo');
                if (visibleCount === 0) {
                    recordsInfo.textContent = 'Tidak ada data yang sesuai dengan pencarian';
                } else {
                    recordsInfo.textContent = `Menampilkan ${visibleCount} dari {{ count($alats) }} data alat`;
                }
            });

            document.getElementById('recordsInfo').textContent = `Menampilkan {{ count($alats) }} data alat`;
        </script>
    </div>
</x-admin-layout>