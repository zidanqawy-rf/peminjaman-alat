<x-admin-layout title="Admin Dashboard">
    {{-- 
        Wrapper utama: md:ml-64 untuk memberi ruang sidebar fixed (w-64) di desktop.
        Di mobile, sidebar tidak fixed (drawer), jadi tidak perlu margin.
    --}}
    <div class="md:ml-64 min-h-screen bg-gray-100">
        <div class="p-6" x-data="{ openAdd: false }">

            <!-- Welcome Card -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Greeting Card -->
                <div class="md:col-span-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-8 text-white">
                    <h3 class="text-3xl font-bold mb-2">Selamat datang, Admin 👋</h3>
                    <p class="text-blue-100">Kelola sistem peminjaman alat dengan mudah dan efisien.</p>
                </div>

                <!-- Quick Stats 1 -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Users</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalUsers ?? 0 }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats 2 -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Peminjaman</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalPeminjaman ?? 0 }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Peminjaman Aktif -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Peminjaman Aktif</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $peminjamanAktif ?? 0 }}</p>
                            <p class="text-xs text-gray-400 mt-1">Disetujui & Sedang Dipinjam</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Peminjaman Selesai -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Peminjaman Selesai</p>
                            <p class="text-2xl font-bold text-green-600">{{ $peminjamanSelesai ?? 0 }}</p>
                            <p class="text-xs text-gray-400 mt-1">Berhasil dikembalikan</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                <h4 class="text-xl font-bold text-gray-800 mb-6">Aksi Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Add User Button -->
                    <button @click="openAdd = true"
                        class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="font-semibold text-lg">Tambah User Baru</span>
                    </button>

                    <!-- View Users Button -->
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="font-semibold text-lg">Kelola User</span>
                    </a>

                    <!-- Log Aktivitas Button -->
                    <a href="{{ route('admin.log-aktivitas.index') }}"
                        class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                        <span class="font-semibold text-lg">Log Aktivitas</span>
                    </a>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature Card 1 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Kelola User</h5>
                    <p class="text-gray-600 text-sm">Tambah, edit, dan hapus pengguna dengan mudah.</p>
                </div>

                <!-- Feature Card 2 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Kelola Alat</h5>
                    <p class="text-gray-600 text-sm">Pantau dan kelola inventaris alat dengan efisien.</p>
                </div>

                <!-- Feature Card 3 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Log Aktivitas</h5>
                    <p class="text-gray-600 text-sm">Monitor semua aktivitas peminjaman alat secara real-time.</p>
                </div>
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
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-800 mb-1">Konfirmasi Password</label>
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

        </div>
    </div>
</x-admin-layout>