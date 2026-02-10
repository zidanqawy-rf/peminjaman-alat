<x-admin-layout title="Kelola User">
    <div x-data="{ openAdd: false, openEdit: false, editUserId: null, editUserData: null, deleteUserId: null, openDelete: false, openImport: false, importFile: null, importLoading: false }">
        <!-- Success Message -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-800">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <!-- Header with Add Button -->
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Daftar User</h3>
            <div class="flex gap-3">
                <button @click="openImport = true"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                        </path>
                    </svg>
                    Import Excel
                </button>
                <button @click="openAdd = true"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah User
                </button>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama, email, atau role..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table id="usersTable" class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Jurusan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">NISN</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <span class="font-medium">{{ $user->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($user->isAdmin())
                                        <span
                                            class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Admin</span>
                                    @elseif ($user->isPetugas())
                                        <span
                                            class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-semibold">Petugas</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">User</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->kelas }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->jurusan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->nisn }}</td>
                                <td class="px-6 py-4 text-sm flex items-center space-x-3">
                                    <button
                                        @click="editUserId = {{ $user->id }}; editUserData = {name: '{{ $user->name }}', email: '{{ $user->email }}', role: '{{ $user->role }}'}; openEdit = true"
                                        class="px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors inline-flex items-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        <span class="ml-1">Edit</span>
                                    </button>

                                    <button @click="deleteUserId = {{ $user->id }}; openDelete = true"
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
                                            d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>
                                    </svg>
                                    <p>Belum ada data user.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div class="mt-4 px-6 py-3 bg-gray-50 border-t border-gray-200 text-sm text-gray-600">
                <span id="recordsInfo">Menampilkan data user</span>
            </div>

            <!-- Modal Tambah User -->
            <x-admin-modal id="Add" title="Tambah User Baru">
                <form action="{{ route('admin.register') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-800 mb-1">Nama</label>
                        <input type="text" name="name" id="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            value="{{ old('name') }}" required />
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-800 mb-1">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            value="{{ old('email') }}" required />
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-800 mb-1">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                            required />
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-800 mb-1">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required />
                    </div>

                    <!-- Role Field -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-800 mb-1">Role</label>
                        <select name="role" id="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="user">User</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>

                    <!-- Kelas Field -->
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-800 mb-1">Kelas</label>
                        <input type="text" name="kelas" id="kelas"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kelas') border-red-500 @enderror"
                            value="{{ old('kelas') }}" required />
                        @error('kelas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jurusan Field -->
                    <div>
                        <label for="jurusan" class="block text-sm font-medium text-gray-800 mb-1">Jurusan</label>
                        <input type="text" name="jurusan" id="jurusan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jurusan') border-red-500 @enderror"
                            value="{{ old('jurusan') }}" required />
                        @error('jurusan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NISN Field -->
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-800 mb-1">NISN</label>
                        <input type="number" name="nisn" id="nisn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nisn') border-red-500 @enderror"
                            value="{{ old('nisn') }}" required />
                        @error('nisn')
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

            <!-- Modal Edit User -->
            <x-admin-modal id="Edit" title="Edit User">
                <template x-if="editUserId && editUserData">
                    <form :action="`/admin/users/${editUserId}`" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Name Field -->
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-800 mb-1">Nama</label>
                            <input type="text" name="name" id="edit_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :value="editUserData?.name || ''" required />
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-800 mb-1">Email</label>
                            <input type="email" name="email" id="edit_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :value="editUserData?.email || ''" required />
                        </div>

                        <!-- Role Field -->
                        <div>
                            <label for="edit_role" class="block text-sm font-medium text-gray-800 mb-1">Role</label>
                            <select name="role" id="edit_role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :value="editUserData?.role || 'user'">
                                <option value="user">User</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="edit_password" class="block text-sm font-medium text-gray-800 mb-1">Password
                                (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" id="edit_password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="edit_password_confirmation"
                                class="block text-sm font-medium text-gray-800 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="edit_password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Kelas Field -->
                        <div>
                            <label for="edit_kelas" class="block text-sm font-medium text-gray-800 mb-1">Kelas</label>
                            <input type="text" name="kelas" id="edit_kelas"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :value="editUserData?.kelas || ''" required />
                        </div>

                        <!-- Jurusan Field -->
                        <div>
                            <label for="edit_jurusan" class="block text-sm font-medium text-gray-800 mb-1">Jurusan</label>
                            <input type="text" name="jurusan" id="edit_jurusan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :value="editUserData?.jurusan || ''" required />
                        </div>

                        <!-- NISN Field -->
                        <div>
                            <label for="edit_nisn" class="block text-sm font-medium text-gray-800 mb-1">NISN</label>
                            <input type="number" name="nisn" id="edit_nisn"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :value="editUserData?.nisn || ''" required />
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

            <!-- Modal Hapus User -->
            <div x-show="openDelete" @click="openDelete = false"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
                style="display: none;">
                <div @click.stop class="bg-white rounded-lg shadow-xl max-w-sm w-full">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Hapus User</h3>
                        <button @click="openDelete = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6">
                        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak
                            dapat
                            diubah.</p>

                        <!-- Buttons -->
                        <div class="flex items-center space-x-3">
                            <template x-if="deleteUserId">
                                <form :action="`/admin/users/${deleteUserId}`" method="POST" class="flex-1">
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
        </div>

        <script>
            // Simple search functionality
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const tableRows = document.querySelectorAll('#usersTable tbody tr');
                let visibleCount = 0;

                tableRows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const email = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                    const role = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    const jurusan = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';
                    const nisn = row.querySelector('td:nth-child(6)')?.textContent.toLowerCase() || '';

                    const matches = name.includes(searchTerm) ||
                        email.includes(searchTerm) ||
                        role.includes(searchTerm) ||
                        jurusan.includes(searchTerm) ||
                        nisn.includes(searchTerm);

                    if (matches) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update records info
                const recordsInfo = document.getElementById('recordsInfo');
                if (visibleCount === 0) {
                    recordsInfo.textContent = 'Tidak ada data yang sesuai dengan pencarian';
                } else {
                    recordsInfo.textContent = `Menampilkan ${visibleCount} dari {{ count($users) }} data user`;
                }
            });

            // Set initial records count
            document.getElementById('recordsInfo').textContent = `Menampilkan {{ count($users) }} data user`;
        </script>

        <!-- Modal Import Excel -->
        <div x-show="openImport"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            @click.self="openImport = false" style="display: none;">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-96 overflow-y-auto">
                <!-- Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Import Data User dari Excel</h3>
                    <button @click="openImport = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-4">
                    <form id="importForm" class="space-y-4">
                        @csrf

                        <!-- Format Guide -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">Format File CSV</h4>
                            <p class="text-sm text-blue-800 mb-2">File harus memiliki 4 kolom dengan urutan:</p>
                            <div class="grid grid-cols-2 gap-2 text-sm text-blue-800">
                                <div>1. <strong>Nama</strong> (nama lengkap)</div>
                                <div>2. <strong>Email</strong> (email@domain.com)</div>
                                <div>3. <strong>Password</strong> (minimal 8 karakter)</div>
                                <div>4. <strong>Role</strong> (user/petugas/admin)</div>
                                <div>5. <strong>Kelas</strong> (nama kelas)</div>
                                <div>6. <strong>NISN</strong> (nomor induk siswa nasional)</div>
                                <div>7. <strong>Jurusan</strong> (nama jurusan)</div>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-800">Pilih File Excel/CSV</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition-colors"
                                id="dropZone">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <p class="text-gray-600">Drag and drop file di sini atau <span
                                        class="text-blue-600 font-medium">klik untuk browse</span></p>
                                <p class="text-sm text-gray-500 mt-1">Format: CSV, XLSX, XLS</p>
                                <input type="file" id="fileInput" name="file" accept=".csv,.xlsx,.xls,.txt"
                                    class="hidden">
                            </div>
                            <div id="filePreview" class="text-sm text-gray-600" style="display: none;">
                                File dipilih: <span id="fileName" class="font-semibold text-blue-600"></span>
                            </div>
                        </div>

                        <!-- Download Template Button -->
                        <div class="flex justify-center">
                            <a href="{{ route('admin.users.template') }}" download
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Template CSV
                            </a>
                        </div>

                        <!-- Error Messages -->
                        <div id="importErrors" class="space-y-2" style="display: none;">
                            <h4 class="font-semibold text-red-700">Errors:</h4>
                            <div id="errorsList"
                                class="bg-red-50 border border-red-200 rounded p-3 text-sm text-red-700"></div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3">
                    <button @click="openImport = false"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                        Batal
                    </button>
                    <button id="submitImport" type="submit" form="importForm"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        Upload & Import
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Import Modal Functionality
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const importForm = document.getElementById('importForm');
            const submitImport = document.getElementById('submitImport');

            dropZone.addEventListener('click', () => fileInput.click());

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
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    updateFilePreview();
                }
            });

            fileInput.addEventListener('change', updateFilePreview);

            function updateFilePreview() {
                if (fileInput.files.length > 0) {
                    fileName.textContent = fileInput.files[0].name;
                    filePreview.style.display = 'block';
                    submitImport.disabled = false;
                } else {
                    filePreview.style.display = 'none';
                    submitImport.disabled = true;
                }
            }

            importForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                if (!fileInput.files.length) {
                    alert('Pilih file terlebih dahulu');
                    return;
                }

                const formData = new FormData(importForm);
                submitImport.disabled = true;
                submitImport.textContent = 'Sedang diproses...';

                try {
                    const response = await fetch('{{ route('admin.users.import') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        alert('✓ ' + data.message);
                        // Reload halaman
                        window.location.reload();
                    } else {
                        // Tampilkan errors
                        const errorsList = document.getElementById('errorsList');
                        const importErrors = document.getElementById('importErrors');
                        
                        if (data.errors && data.errors.length > 0) {
                            errorsList.innerHTML = data.errors.map(err => 
                                `<div>• ${err}</div>`
                            ).join('');
                            importErrors.style.display = 'block';
                        } else {
                            alert('❌ ' + (data.message || 'Terjadi kesalahan'));
                        }
                        submitImport.disabled = false;
                        submitImport.textContent = 'Upload & Import';
                    }
                } catch (error) {
                    alert('❌ Gagal mengupload file: ' + error.message);
                    submitImport.disabled = false;
                    submitImport.textContent = 'Upload & Import';
                }
            });
        </script>
</x-admin-layout>
