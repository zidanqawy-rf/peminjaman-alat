<x-petugas-layout>
    <x-slot name="title">Detail Peminjaman #{{ $peminjaman->id }}</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detail Peminjaman #{{ $peminjaman->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            
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

            <!-- Informasi Peminjaman -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Peminjaman</h3>
                        
                        <!-- Status Badge -->
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                            @if($peminjaman->status === 'menunggu') bg-yellow-100 text-yellow-800
                            @elseif($peminjaman->status === 'disetujui') bg-blue-100 text-blue-800
                            @elseif($peminjaman->status === 'dipinjam') bg-purple-100 text-purple-800
                            @elseif($peminjaman->status === 'pengajuan_pengembalian') bg-orange-100 text-orange-800
                            @elseif($peminjaman->status === 'di_denda') bg-red-100 text-red-800
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
                                <p class="text-sm font-medium text-gray-500">Peminjam</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $peminjaman->user->email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Alat</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->alat->nama ?? 'Alat tidak ditemukan' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Jumlah</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->jumlah }} unit</p>
                            </div>

                            @if($peminjaman->keperluan)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Keperluan</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->keperluan }}</p>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Pinjam</p>
                                <p class="mt-1 text-base text-gray-900">
                                    {{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') : '-' }}
                                </p>
                            </div>
                            
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
                                
                                @php
                                    $tanggalRencana = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
                                    $tanggalActual = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_actual)->startOfDay();
                                    $isLateActual = $tanggalActual->gt($tanggalRencana);
                                @endphp
                                
                                @if($isLateActual)
                                    <span class="mt-1 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                        Terlambat {{ $peminjaman->jumlah_hari_terlambat }} hari
                                    </span>
                                @else
                                    <span class="mt-1 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                        Tepat Waktu
                                    </span>
                                @endif
                            </div>
                            @endif

                            @if($peminjaman->denda > 0)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Denda</p>
                                <p class="mt-1 text-base font-semibold text-red-600">
                                    Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                </p>
                                @if($peminjaman->status_pembayaran_denda === 'terverifikasi')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Terverifikasi</span>
                                @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">Menunggu Verifikasi</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Belum Bayar</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Foto Pengembalian -->
                    @if($peminjaman->foto_pengembalian)
                    <div class="mt-6 border-t pt-6">
                        <p class="text-sm font-medium text-gray-500 mb-3">Foto Pengembalian</p>
                        <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}" 
                             alt="Foto Pengembalian" 
                             class="max-w-md rounded-lg border shadow-sm">
                    </div>
                    @endif

                    <!-- Bukti Pembayaran Denda -->
                    @if($peminjaman->bukti_pembayaran_denda)
                    <div class="mt-6 border-t pt-6">
                        <p class="text-sm font-medium text-gray-500 mb-3">Bukti Pembayaran Denda</p>
                        <img src="{{ asset('storage/' . $peminjaman->bukti_pembayaran_denda) }}" 
                             alt="Bukti Pembayaran" 
                             class="max-w-md rounded-lg border shadow-sm">
                        @if($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                        <p class="mt-2 text-sm text-yellow-600">Menunggu verifikasi</p>
                        @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                        <p class="mt-2 text-sm text-green-600">Sudah diverifikasi</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- ACTION: APPROVE/REJECT (Status: Menunggu) -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'menunggu')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Tindakan Persetujuan</h4>
                    <div class="flex space-x-3">
                        <form action="{{ route('petugas.peminjaman.approve', $peminjaman) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                Setujui Peminjaman
                            </button>
                        </form>
                        
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Tolak Peminjaman
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: SERAHKAN ALAT (Status: Disetujui) -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'disetujui')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Serahkan Alat</h4>
                    <p class="mb-4 text-sm text-gray-600">Klik tombol di bawah setelah alat diserahkan kepada peminjam.</p>
                    <form action="{{ route('petugas.peminjaman.serahkan', $peminjaman) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                            Konfirmasi Penyerahan Alat
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: STATUS DIPINJAM - Petugas Inisiasi Pengembalian Paksa -->
            <!-- Digunakan jika user terlambat dan petugas ingin langsung -->
            <!-- memproses pengembalian tanpa menunggu user ajukan pengembalian -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'dipinjam')
            @php
                $tanggalRencana = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
                $tanggalHariIni = \Carbon\Carbon::now()->startOfDay();
                $hariTerlambat  = $tanggalHariIni->gt($tanggalRencana) ? $tanggalRencana->diffInDays($tanggalHariIni) : 0;
                $isTerlambat    = $hariTerlambat > 0;
                $dendaPerHari   = 5000;
                $estimasiDenda  = $hariTerlambat * $dendaPerHari;
            @endphp

            @if($isTerlambat)
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Warning Terlambat -->
                    <div class="mb-4 rounded-md bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">TANGGAL TIDAK SESUAI - Pengembalian Terlambat!</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Tanggal Rencana: <strong>{{ $tanggalRencana->format('d M Y') }}</strong></p>
                                    <p>Tanggal Hari Ini: <strong>{{ $tanggalHariIni->format('d M Y') }}</strong></p>
                                    <p class="mt-1">Terlambat <strong>{{ $hariTerlambat }} hari</strong></p>
                                    <p class="mt-1">Estimasi denda: <strong>Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opsi: Petugas Inisiasi Pengembalian Langsung dengan Denda -->
                    <div class="rounded-md bg-orange-50 border-l-4 border-orange-500 p-4">
                        <h4 class="text-sm font-medium text-orange-800 mb-2">
                            Opsi: Proses Pengembalian Langsung (Tanpa Menunggu User)
                        </h4>
                        <p class="text-sm text-orange-700 mb-3">
                            Gunakan ini jika alat sudah ada di tangan Anda. Alat akan dikembalikan ke stok dan status berubah menjadi 
                            <strong>"Di Denda"</strong> agar user bisa upload bukti pembayaran.
                        </p>
                        <button onclick="document.getElementById('kembalikanDendaDariDipinjamModal').classList.remove('hidden')"
                            class="inline-flex items-center rounded-md bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
                            Kembalikan Alat &amp; Set Status "Di Denda"
                        </button>
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: VERIFIKASI PENGEMBALIAN (Status: Pengajuan Pengembalian) -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'pengajuan_pengembalian')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Verifikasi Pengembalian</h4>
                    
                    @php
                        $tanggalRencana2 = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
                        $tanggalActual2  = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_actual)->startOfDay();
                        $isLate2         = $tanggalActual2->gt($tanggalRencana2);
                        $hariTerlambat2  = $isLate2 ? $tanggalRencana2->diffInDays($tanggalActual2) : 0;
                    @endphp

                    @if($isLate2)
                    <div class="mb-4 rounded-md bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">TANGGAL TIDAK SESUAI - Pengembalian Terlambat!</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Tanggal Rencana: <strong>{{ $tanggalRencana2->format('d M Y') }}</strong></p>
                                    <p>Tanggal Aktual: <strong>{{ $tanggalActual2->format('d M Y') }}</strong></p>
                                    <p class="mt-1">Terlambat <strong>{{ $hariTerlambat2 }} hari</strong></p>
                                    <p class="mt-1">Denda: <strong class="text-lg">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Kembalikan dengan Status Di Denda -->
                    <div class="mb-4 rounded-md bg-orange-50 border-l-4 border-orange-500 p-4">
                        <p class="text-sm font-medium text-orange-800 mb-3">
                            <strong>Opsi Kembalikan:</strong> Kembalikan alat ke stok sekarang dan ubah status menjadi "Di Denda".
                            User akan diminta upload bukti pembayaran.
                        </p>
                        <button onclick="document.getElementById('kembalikanDendaModal').classList.remove('hidden')"
                            class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Kembalikan Alat (Status: Di Denda)
                        </button>
                    </div>
                    @else
                    <div class="mb-4 rounded-md bg-green-50 border-l-4 border-green-500 p-4">
                        <h3 class="text-sm font-medium text-green-800">TANGGAL SESUAI - Pengembalian Tepat Waktu</h3>
                        <p class="mt-1 text-sm text-green-700">Tidak ada denda</p>
                    </div>
                    @endif

                    <!-- Form Verifikasi Normal -->
                    @if($peminjaman->denda > 0)
                        @if($peminjaman->status_pembayaran_denda === 'terverifikasi')
                            <form action="{{ route('petugas.peminjaman.terima-kembali', $peminjaman) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                                    <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Kondisi</option>
                                        <option value="baik">Baik</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Catatan Petugas</label>
                                    <textarea name="catatan_petugas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan..."></textarea>
                                </div>
                                <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                    Terima Pengembalian (Denda Sudah Terverifikasi)
                                </button>
                            </form>
                        @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                            <div class="mb-4 rounded-md bg-yellow-50 border-l-4 border-yellow-500 p-4">
                                <p class="text-sm font-medium text-yellow-800">Peminjam sudah upload bukti pembayaran. Silakan verifikasi.</p>
                            </div>
                            <div class="flex space-x-3">
                                <form action="{{ route('petugas.peminjaman.verifikasi-pembayaran', $peminjaman) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                        Verifikasi Pembayaran Denda
                                    </button>
                                </form>
                                <button onclick="document.getElementById('tolakPembayaranModal').classList.remove('hidden')"
                                    class="flex-1 rounded-md bg-red-600 px-4 py-3 text-sm font-medium text-white hover:bg-red-700">
                                    Tolak Pembayaran
                                </button>
                            </div>
                        @else
                            <div class="rounded-md bg-red-50 border-l-4 border-red-500 p-4">
                                <p class="text-sm font-medium text-red-800">Peminjam belum upload bukti pembayaran denda. Gunakan tombol "Kembalikan Alat (Status: Di Denda)" di atas agar user bisa upload bukti.</p>
                            </div>
                        @endif
                    @else
                        <form action="{{ route('petugas.peminjaman.terima-kembali', $peminjaman) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                                <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Kondisi</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan Petugas</label>
                                <textarea name="catatan_petugas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan..."></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                Terima Pengembalian
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: STATUS DI DENDA -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'di_denda')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Status: Di Denda</h4>
                    <div class="mb-4 rounded-md bg-orange-50 border-l-4 border-orange-500 p-4">
                        <p class="text-sm text-orange-800">
                            Alat sudah dikembalikan ke stok. Menunggu user membayar denda sebesar 
                            <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong>
                        </p>
                    </div>
                    
                    @if($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                        <div class="mb-4 rounded-md bg-yellow-50 border-l-4 border-yellow-500 p-4">
                            <p class="text-sm font-medium text-yellow-800">Peminjam sudah upload bukti pembayaran. Silakan verifikasi untuk menyelesaikan peminjaman.</p>
                        </div>
                        <div class="flex space-x-3">
                            <form action="{{ route('petugas.peminjaman.verifikasi-pembayaran', $peminjaman) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                    Verifikasi Pembayaran Denda
                                </button>
                            </form>
                            <button onclick="document.getElementById('tolakPembayaranModal').classList.remove('hidden')"
                                class="flex-1 rounded-md bg-red-600 px-4 py-3 text-sm font-medium text-white hover:bg-red-700">
                                Tolak Pembayaran
                            </button>
                        </div>
                    @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                        <div class="mb-4 rounded-md bg-green-50 border-l-4 border-green-500 p-4">
                            <p class="text-sm font-medium text-green-800">Pembayaran denda sudah diverifikasi. Klik tombol di bawah untuk menyelesaikan peminjaman.</p>
                        </div>
                        <form action="{{ route('petugas.peminjaman.selesaikan-denda', $peminjaman) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                Selesaikan Peminjaman (Ubah Status: Dikembalikan)
                            </button>
                        </form>
                    @else
                        <div class="rounded-md bg-gray-50 border-l-4 border-gray-400 p-4">
                            <p class="text-sm text-gray-600">Menunggu user upload bukti pembayaran denda...</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Tombol Kembali -->
            <div class="flex justify-between">
                <a href="{{ route('petugas.peminjaman.index') }}" 
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: Reject Peminjaman -->
    <!-- ============================================================ -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Tolak Peminjaman</h3>
                <form action="{{ route('petugas.peminjaman.reject', $peminjaman) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="alasan_penolakan" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Tuliskan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: Tolak Pembayaran -->
    <!-- ============================================================ -->
    <div id="tolakPembayaranModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Tolak Pembayaran Denda</h3>
                <form action="{{ route('petugas.peminjaman.tolak-pembayaran', $peminjaman) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_petugas" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Contoh: Bukti tidak jelas, jumlah tidak sesuai..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('tolakPembayaranModal').classList.add('hidden')"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: Kembalikan dengan Denda (dari status: pengajuan_pengembalian) -->
    <!-- ============================================================ -->
    <div id="kembalikanDendaModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Kembalikan Alat &ndash; Status "Di Denda"</h3>
                <div class="mb-4 rounded-md bg-orange-50 p-4">
                    <p class="text-sm text-orange-800">
                        Alat akan dikembalikan ke stok dan status berubah menjadi <strong>"Di Denda"</strong>. 
                        User masih perlu membayar denda <strong>Rp {{ number_format($peminjaman->denda ?? 0, 0, ',', '.') }}</strong>.
                    </p>
                </div>
                <form action="{{ route('petugas.peminjaman.kembalikan-denda', $peminjaman) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                        <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <option value="">Pilih Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Catatan Petugas</label>
                        <textarea name="catatan_petugas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div class="mt-4 space-y-2">
                        <button type="submit"
                            class="w-full rounded-md bg-orange-600 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            Kembalikan Alat &amp; Set Status "Di Denda"
                        </button>
                        <button type="button" onclick="document.getElementById('kembalikanDendaModal').classList.add('hidden')"
                            class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: Kembalikan Paksa dengan Denda (dari status: dipinjam) -->
    <!-- Digunakan petugas jika alat sudah ada di tangan tapi user belum ajukan -->
    <!-- ============================================================ -->
    <div id="kembalikanDendaDariDipinjamModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Proses Pengembalian Langsung</h3>
                <div class="mb-4 rounded-md bg-orange-50 p-4">
                    <p class="text-sm text-orange-800">
                        Alat akan dikembalikan ke stok dan status berubah menjadi <strong>"Di Denda"</strong>. 
                        Estimasi denda: <strong>Rp {{ number_format($estimasiDenda ?? 0, 0, ',', '.') }}</strong> 
                        ({{ $hariTerlambat ?? 0 }} hari &times; Rp 5.000).
                    </p>
                </div>
                {{-- Route kembalikan-denda menerima dari pengajuan_pengembalian --}}
                {{-- Untuk dari dipinjam, kita gunakan route baru: kembalikan-denda-paksa --}}
                <form action="{{ route('petugas.peminjaman.kembalikan-denda-paksa', $peminjaman) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                        <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <option value="">Pilih Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Kembali Aktual</label>
                        <input type="date" name="tanggal_kembali_actual" value="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Catatan Petugas</label>
                        <textarea name="catatan_petugas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div class="mt-4 space-y-2">
                        <button type="submit"
                            class="w-full rounded-md bg-orange-600 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            Proses Pengembalian &amp; Set Status "Di Denda"
                        </button>
                        <button type="button" onclick="document.getElementById('kembalikanDendaDariDipinjamModal').classList.add('hidden')"
                            class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-petugas-layout>