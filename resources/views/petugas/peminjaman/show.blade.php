<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Peminjaman #{{ $peminjaman->id }}
            </h2>
            <a href="{{ route('petugas.peminjaman.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Status Badge --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Status Peminjaman</h3>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($peminjaman->status === 'menunggu') bg-yellow-200 text-yellow-800
                            @elseif($peminjaman->status === 'disetujui') bg-blue-200 text-blue-800
                            @elseif($peminjaman->status === 'dipinjam') bg-green-200 text-green-800
                            @elseif($peminjaman->status === 'pengajuan_pengembalian') bg-purple-200 text-purple-800
                            @elseif($peminjaman->status === 'dikembalikan') bg-gray-200 text-gray-800
                            @elseif($peminjaman->status === 'ditolak') bg-red-200 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                        </span>
                    </div>

                    {{-- Informasi Denda --}}
                    @if($peminjaman->denda > 0)
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Total Denda</p>
                            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                            @if($peminjaman->status_pembayaran_denda === 'terverifikasi')
                                <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    ✓ Sudah Terverifikasi
                                </span>
                            @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                                <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    ⏳ Menunggu Verifikasi
                                </span>
                            @else
                                <span class="inline-block mt-2 px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                    ✗ Belum Dibayar
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Data Peminjam --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Peminjam</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama</p>
                        <p class="font-semibold">{{ $peminjaman->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $peminjaman->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold">{{ $peminjaman->user->no_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIM/NIP</p>
                        <p class="font-semibold">{{ $peminjaman->user->nim_nip ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Data Alat --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Alat</h3>
                @if($peminjaman->alat)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Alat</p>
                            <p class="font-semibold">{{ $peminjaman->alat->nama ?? $peminjaman->alat->nama_alat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kode Alat</p>
                            <p class="font-semibold">{{ $peminjaman->alat->kode ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Dipinjam</p>
                            <p class="font-semibold">{{ $peminjaman->jumlah }} unit</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kategori</p>
                            <p class="font-semibold">{{ optional($peminjaman->alat->kategori)->nama ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4">
                        <p class="text-yellow-800">⚠️ Data alat tidak tersedia (Alat mungkin sudah dihapus)</p>
                        <p class="text-sm text-yellow-700 mt-1">Jumlah Dipinjam: {{ $peminjaman->jumlah }} unit</p>
                    </div>
                @endif
            </div>

            {{-- Timeline Peminjaman --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Timeline Peminjaman</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="font-semibold">{{ $peminjaman->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pinjam</p>
                        <p class="font-semibold">{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y, H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Rencana Kembali</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Kembali Aktual</p>
                        <p class="font-semibold">{{ $peminjaman->tanggal_kembali_actual ? $peminjaman->tanggal_kembali_actual->format('d M Y, H:i') : '-' }}</p>
                    </div>
                </div>

                {{-- INFO KETERLAMBATAN & DENDA --}}
                @if($peminjaman->jumlah_hari_terlambat > 0 || $peminjaman->denda > 0)
                    <div class="mt-4 p-4 bg-red-50 border-2 border-red-300 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-800 font-semibold text-lg">
                                    ⚠️ Terlambat {{ $peminjaman->jumlah_hari_terlambat ?? $peminjaman->hari_terlambat }} hari
                                </p>
                                <p class="text-sm text-red-600 mt-1">
                                    Tanggal Rencana: {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }} | 
                                    Tanggal Aktual: {{ $peminjaman->tanggal_kembali_actual ? $peminjaman->tanggal_kembali_actual->format('d M Y') : '-' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-red-600">Total Denda</p>
                                <p class="text-2xl font-bold text-red-700">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Keperluan --}}
            @if($peminjaman->keperluan || $peminjaman->catatan)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Keperluan</h3>
                    <p class="text-gray-700">{{ $peminjaman->keperluan ?? $peminjaman->catatan ?? '-' }}</p>
                </div>
            @endif

            {{-- SECTION: VERIFIKASI PEMBAYARAN DENDA --}}
            @if($peminjaman->denda > 0 && $peminjaman->bukti_pembayaran_denda)
                <div class="bg-yellow-50 border-2 border-yellow-300 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-800">💰 Verifikasi Pembayaran Denda</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Kiri: Info Pembayaran --}}
                        <div>
                            <div class="bg-white p-4 rounded-lg shadow-sm space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Total Denda</p>
                                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status Pembayaran</p>
                                    <p class="font-semibold">
                                        @if($peminjaman->status_pembayaran_denda === 'terverifikasi')
                                            <span class="text-green-600">✓ Terverifikasi</span>
                                        @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                                            <span class="text-yellow-600">⏳ Menunggu Verifikasi</span>
                                        @else
                                            <span class="text-red-600">✗ Belum Bayar</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Waktu Upload Bukti</p>
                                    <p class="font-semibold">{{ $peminjaman->updated_at->format('d M Y, H:i') }}</p>
                                </div>

                                @if($peminjaman->catatan_admin_pembayaran)
                                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                                        <p class="text-sm font-semibold text-red-800 mb-1">Catatan Penolakan:</p>
                                        <p class="text-sm text-red-700">{{ $peminjaman->catatan_admin_pembayaran }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Kanan: Bukti Pembayaran --}}
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2">Bukti Pembayaran:</p>
                            <div class="bg-white p-2 rounded-lg shadow-sm">
                                <img src="{{ asset('storage/' . $peminjaman->bukti_pembayaran_denda) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="w-full h-auto max-h-96 object-contain rounded cursor-pointer hover:opacity-90"
                                     onclick="window.open(this.src, '_blank')">
                                <p class="text-xs text-gray-500 text-center mt-2">Klik gambar untuk memperbesar</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi Verifikasi --}}
                    @if($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                        <div class="mt-6 flex gap-3">
                            {{-- Form Verifikasi --}}
                            <form action="{{ route('petugas.peminjaman.verifikasi-pembayaran', $peminjaman) }}" 
                                  method="POST" 
                                  class="flex-1"
                                  onsubmit="return confirm('Apakah Anda yakin pembayaran ini sudah benar dan ingin diverifikasi?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                                    ✓ Verifikasi Pembayaran
                                </button>
                            </form>

                            {{-- Form Tolak --}}
                            <button type="button" 
                                    onclick="document.getElementById('modal-tolak-pembayaran').classList.remove('hidden')"
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                                ✗ Tolak Pembayaran
                            </button>
                        </div>
                    @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                        <div class="mt-6 p-4 bg-green-100 border border-green-300 rounded-lg text-center">
                            <p class="text-green-800 font-semibold">✓ Pembayaran sudah terverifikasi</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Foto Pengembalian --}}
            @if($peminjaman->foto_pengembalian)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Foto Pengembalian (dari User)</h3>
                    <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}" 
                         alt="Foto Pengembalian" 
                         class="max-w-md rounded shadow cursor-pointer hover:opacity-90"
                         onclick="window.open(this.src, '_blank')">
                    <p class="text-xs text-gray-500 mt-2">Klik untuk memperbesar</p>
                </div>
            @endif

            {{-- Kondisi & Catatan Petugas --}}
            @if($peminjaman->status === 'dikembalikan')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Hasil Verifikasi Pengembalian</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Kondisi Alat</p>
                            <p class="font-semibold capitalize">{{ $peminjaman->kondisi_alat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Catatan Petugas</p>
                            <p class="font-semibold">{{ $peminjaman->catatan_petugas ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Alasan Penolakan --}}
            @if($peminjaman->status === 'ditolak' && $peminjaman->alasan_penolakan)
                <div class="bg-red-50 border border-red-200 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-red-800">Alasan Penolakan</h3>
                    <p class="text-red-700">{{ $peminjaman->alasan_penolakan }}</p>
                </div>
            @endif

            {{-- TOMBOL AKSI PETUGAS --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Aksi Petugas</h3>
                
                <div class="flex gap-3 flex-wrap">
                    {{-- Approve --}}
                    @if($peminjaman->status === 'menunggu')
                        <form action="{{ route('petugas.peminjaman.approve', $peminjaman) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
                                    onclick="return confirm('Setujui peminjaman ini?')">
                                Setujui Peminjaman
                            </button>
                        </form>

                        <button type="button" 
                                onclick="document.getElementById('modal-tolak').classList.remove('hidden')"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                            Tolak Peminjaman
                        </button>
                    @endif

                    {{-- Serahkan Alat --}}
                    @if($peminjaman->status === 'disetujui')
                        <form action="{{ route('petugas.peminjaman.serahkan', $peminjaman) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded"
                                    onclick="return confirm('Alat sudah diserahkan ke peminjam?')">
                                Serahkan Alat
                            </button>
                        </form>
                    @endif

                    {{-- Terima Pengembalian --}}
                    @if($peminjaman->status === 'pengajuan_pengembalian')
                        <button type="button" 
                                onclick="document.getElementById('modal-terima-kembali').classList.remove('hidden')"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded">
                            Terima Pengembalian
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL: Tolak Peminjaman --}}
    <div id="modal-tolak" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-semibold mb-4">Tolak Peminjaman</h3>
            <form action="{{ route('petugas.peminjaman.reject', $peminjaman) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" 
                              rows="4" 
                              required
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                              placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                        Tolak
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('modal-tolak').classList.add('hidden')"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Tolak Pembayaran Denda --}}
    <div id="modal-tolak-pembayaran" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-semibold mb-4">Tolak Pembayaran Denda</h3>
            <form action="{{ route('petugas.peminjaman.tolak-pembayaran', $peminjaman) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea name="catatan_petugas" 
                              rows="4" 
                              required
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                              placeholder="Contoh: Bukti pembayaran tidak jelas, nominal tidak sesuai, dll..."></textarea>
                    @error('catatan_petugas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                        Tolak Pembayaran
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('modal-tolak-pembayaran').classList.add('hidden')"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Terima Pengembalian --}}
    <div id="modal-terima-kembali" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-semibold mb-4">Verifikasi Pengembalian</h3>
            <form action="{{ route('petugas.peminjaman.terima-kembali', $peminjaman) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Alat *</label>
                    <select name="kondisi_alat" 
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Petugas</label>
                    <textarea name="catatan_petugas" 
                              rows="3" 
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded">
                        Terima Pengembalian
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('modal-terima-kembali').classList.add('hidden')"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>