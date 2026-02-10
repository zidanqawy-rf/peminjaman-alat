<x-admin-layout title="Kelola Kategori">
    <div x-data="{ 
        openAdd: false, 
        openEdit: false, 
        editKategoriId: null, 
        editKategoriData: null, 
        deleteKategoriId: null, 
        openDelete: false
    }">
        <!-- Success/Error Message -->
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

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Header with Add Button -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Kategori</h1>
                <p class="text-gray-600">Tambah, edit, atau hapus kategori alat</p>
            </div>
            <button @click="openAdd = true"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori Baru
            </button>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari kategori..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Kategori Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table id="kategorisTable" class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Jumlah Alat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $index => $kategori)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <span class="font-medium">{{ $kategori->nama }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $kategori->deskripsi ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $kategori->alats_count }} alat
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm flex items-center space-x-3">
                                    <button
                                        @click="editKategoriId = {{ $kategori->id }}; editKategoriData = {nama: '{{ $kategori->nama }}', deskripsi: '{{ $kategori->deskripsi }}'}; openEdit = true"
                                        class="px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors inline-flex items-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        <span class="ml-1">Edit</span>
                                    </button>

                                    <button @click="deleteKategoriId = {{ $kategori->id }}; openDelete = true"
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
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    <p>Belum ada data kategori.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div class="mt-4 px-6 py-3 bg-gray-50 border-t border-gray-200 text-sm text-gray-600">
                <span id="recordsInfo">Menampilkan data kategori</span>
            </div>
        </div>

        <!-- Modal Tambah Kategori -->
        <x-admin-modal id="Add" title="Tambah Kategori Baru">
            <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Kategori Field -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-800 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                        value="{{ old('nama') }}" required />
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Field -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-800 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                        placeholder="Opsional - tambahkan deskripsi kategori">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Simpan
                    </button>
                    <button type="button" @click="openAdd = false"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </x-admin-modal>

        <!-- Modal Edit Kategori -->
        <x-admin-modal id="Edit" title="Edit Kategori">
            <template x-if="editKategoriId && editKategoriData">
                <form :action="`/admin/kategori/${editKategoriId}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Nama Kategori Field -->
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-800 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :value="editKategoriData?.nama || ''" required />
                    </div>

                    <!-- Deskripsi Field -->
                    <div>
                        <label for="edit_deskripsi" class="block text-sm font-medium text-gray-800 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Opsional - tambahkan deskripsi kategori"
                            x-text="editKategoriData?.deskripsi || ''"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                            Batal
                        </button>
                    </div>
                </form>
            </template>
        </x-admin-modal>

        <!-- Modal Hapus Kategori -->
        <div x-show="openDelete" @click="openDelete = false"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            style="display: none;">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-sm w-full">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Hapus Kategori</h3>
                    <button @click="openDelete = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat diubah.</p>
                    <div class="flex items-center space-x-3">
                        <template x-if="deleteKategoriId">
                            <form :action="`/admin/kategori/${deleteKategoriId}`" method="POST" class="flex-1">
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

        <script>
            // Search functionality
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const tableRows = document.querySelectorAll('#kategorisTable tbody tr');
                let visibleCount = 0;

                tableRows.forEach(row => {
                    const nama = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const deskripsi = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                    const matches = nama.includes(searchTerm) || deskripsi.includes(searchTerm);

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
                    recordsInfo.textContent = `Menampilkan ${visibleCount} dari {{ count($kategoris) }} data kategori`;
                }
            });

            document.getElementById('recordsInfo').textContent = `Menampilkan {{ count($kategoris) }} data kategori`;
        </script>
    </div>
</x-admin-layout>