<x-petugas-layout>
    <x-slot name="title">Detail Peminjaman #{{ $peminjaman->id }}</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detail Peminjaman #{{ $peminjaman->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-6 rounded-md border-l-4 border-green-500 bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 rounded-md border-l-4 border-red-500 bg-red-50 p-4">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
            @endif

            <!-- INFO PEMINJAMAN -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Peminjaman</h3>
                        @php
                            $badges=['menunggu'=>['yellow','Menunggu'],'disetujui'=>['blue','Disetujui'],
                                     'dipinjam'=>['purple','Dipinjam'],'pengajuan_pengembalian'=>['orange','Pengajuan Kembali'],
                                     'di_denda'=>['red','Di Denda'],'dikembalikan'=>['green','Dikembalikan'],
                                     'ditolak'=>['red','Ditolak']];
                            [$bc,$bl] = $badges[$peminjaman->status] ?? ['gray',ucfirst($peminjaman->status)];
                        @endphp
                        <span class="inline-flex rounded-full bg-{{ $bc }}-100 px-3 py-1 text-xs font-semibold text-{{ $bc }}-800">
                            {{ $bl }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Peminjam</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $peminjaman->user->email }}</p>
                            </div>
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
                                    $rencana = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
                                    $actual  = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_actual)->startOfDay();
                                    $late    = $actual->gt($rencana);
                                @endphp
                                @if($late)
                                <span class="mt-1 inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                    Terlambat {{ $peminjaman->jumlah_hari_terlambat }} hari
                                </span>
                                @else
                                <span class="mt-1 inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    Tepat Waktu
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            @if($peminjaman->keperluan)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Keperluan</p>
                                <p class="mt-1 text-base text-gray-900">{{ $peminjaman->keperluan }}</p>
                            </div>
                            @endif
                            @if($peminjaman->denda > 0)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Denda</p>
                                <p class="mt-1 text-base font-semibold text-red-600">
                                    Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                </p>
                                @if($peminjaman->status_pembayaran_denda === 'terverifikasi')
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Terverifikasi</span>
                                @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">Menunggu Verifikasi</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Belum Bayar</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- DAFTAR ALAT -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">
                        Daftar Alat ({{ $peminjaman->items->count() }} jenis · {{ $peminjaman->items->sum('jumlah') }} unit total)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Nama Alat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Kondisi Kembali</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($peminjaman->items as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ optional($item->alat)->nama ?? 'Alat tidak ditemukan' }}
                                        </div>
                                        @if(optional($item->alat)->kategori)
                                        <div class="text-xs text-gray-500">{{ $item->alat->kategori }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->jumlah }} unit</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $item->kondisi_alat ? ucfirst($item->kondisi_alat) : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Foto -->
            @if($peminjaman->foto_pengembalian)
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 mb-3">Foto Pengembalian</p>
                    <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}"
                         alt="Foto Pengembalian" class="max-w-md rounded-lg border shadow-sm">
                </div>
            </div>
            @endif
            @if($peminjaman->bukti_pembayaran_denda)
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 mb-3">Bukti Pembayaran Denda</p>
                    <img src="{{ asset('storage/' . $peminjaman->bukti_pembayaran_denda) }}"
                         alt="Bukti Pembayaran" class="max-w-md rounded-lg border shadow-sm">
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: APPROVE/REJECT -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'menunggu')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Tindakan Persetujuan</h4>
                    <div class="flex space-x-3">
                        <form action="{{ route('petugas.peminjaman.approve', $peminjaman) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                Setujui Peminjaman
                            </button>
                        </form>
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                                class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Tolak Peminjaman
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: SERAHKAN -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'disetujui')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Serahkan Alat</h4>
                    <p class="mb-4 text-sm text-gray-600">Klik setelah semua alat diserahkan kepada peminjam.</p>
                    <form action="{{ route('petugas.peminjaman.serahkan', $peminjaman) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                            Konfirmasi Penyerahan Alat
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: DIPINJAM — paksa kembali jika terlambat -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'dipinjam')
            @php
                $tRencana    = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
                $tHariIni    = \Carbon\Carbon::now()->startOfDay();
                $hariLambat  = $tHariIni->gt($tRencana) ? $tRencana->diffInDays($tHariIni) : 0;
                $isTerlambat = $hariLambat > 0;
                $estDenda    = $hariLambat * 5000;
            @endphp
            @if($isTerlambat)
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 rounded-md bg-red-50 border-l-4 border-red-500 p-4">
                        <h3 class="text-sm font-medium text-red-800">TERLAMBAT!</h3>
                        <p class="mt-1 text-sm text-red-700">
                            Rencana: <strong>{{ $tRencana->format('d M Y') }}</strong> ·
                            Hari ini: <strong>{{ $tHariIni->format('d M Y') }}</strong> ·
                            Terlambat: <strong>{{ $hariLambat }} hari</strong> ·
                            Estimasi denda: <strong>Rp {{ number_format($estDenda, 0, ',', '.') }}</strong>
                        </p>
                    </div>
                    <div class="rounded-md bg-orange-50 border-l-4 border-orange-500 p-4">
                        <p class="text-sm font-medium text-orange-800 mb-3">Proses pengembalian paksa (alat sudah di tangan Anda):</p>
                        <button onclick="document.getElementById('kembalikanDendaDariDipinjamModal').classList.remove('hidden')"
                                class="rounded-md bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
                            Kembalikan Alat &amp; Set Status "Di Denda"
                        </button>
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: PENGAJUAN PENGEMBALIAN -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'pengajuan_pengembalian')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Verifikasi Pengembalian</h4>
                    @php
                        $r2 = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
                        $a2 = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_actual)->startOfDay();
                        $late2 = $a2->gt($r2);
                        $hari2 = $late2 ? $r2->diffInDays($a2) : 0;
                    @endphp
                    @if($late2)
                    <div class="mb-4 rounded-md bg-red-50 border-l-4 border-red-500 p-4">
                        <p class="text-sm font-medium text-red-800">Terlambat {{ $hari2 }} hari · Denda: Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                    </div>
                    <div class="mb-4 rounded-md bg-orange-50 border-l-4 border-orange-500 p-4">
                        <p class="text-sm font-medium text-orange-800 mb-2">Kembalikan ke stok & ubah status "Di Denda":</p>
                        <button onclick="document.getElementById('kembalikanDendaModal').classList.remove('hidden')"
                                class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Kembalikan Alat (Status: Di Denda)
                        </button>
                    </div>
                    @else
                    <div class="mb-4 rounded-md bg-green-50 border-l-4 border-green-500 p-4">
                        <p class="text-sm font-medium text-green-800">Tepat Waktu — Tidak ada denda</p>
                    </div>
                    @endif

                    @if($peminjaman->denda > 0)
                        @if($peminjaman->status_pembayaran_denda === 'terverifikasi')
                            <form action="{{ route('petugas.peminjaman.terima-kembali', $peminjaman) }}" method="POST" class="space-y-4">
                                @csrf @method('PATCH')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                                    <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Pilih Kondisi</option>
                                        <option value="baik">Baik</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                                <textarea name="catatan_petugas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Catatan..."></textarea>
                                <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                    Terima Pengembalian (Denda Sudah Terverifikasi)
                                </button>
                            </form>
                        @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                            <div class="mb-4 rounded-md bg-yellow-50 border-l-4 border-yellow-500 p-4">
                                <p class="text-sm font-medium text-yellow-800">Peminjam sudah upload bukti pembayaran. Verifikasi sekarang?</p>
                            </div>
                            <div class="flex space-x-3">
                                <form action="{{ route('petugas.peminjaman.verifikasi-pembayaran', $peminjaman) }}" method="POST" class="flex-1">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                        Verifikasi Pembayaran
                                    </button>
                                </form>
                                <button onclick="document.getElementById('tolakPembayaranModal').classList.remove('hidden')"
                                        class="flex-1 rounded-md bg-red-600 px-4 py-3 text-sm font-medium text-white hover:bg-red-700">
                                    Tolak Pembayaran
                                </button>
                            </div>
                        @else
                            <div class="rounded-md bg-red-50 border-l-4 border-red-500 p-4">
                                <p class="text-sm font-medium text-red-800">Peminjam belum upload bukti pembayaran. Gunakan tombol "Kembalikan Alat (Status: Di Denda)" di atas.</p>
                            </div>
                        @endif
                    @else
                        <form action="{{ route('petugas.peminjaman.terima-kembali', $peminjaman) }}" method="POST" class="space-y-4">
                            @csrf @method('PATCH')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                                <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Pilih</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>
                            <textarea name="catatan_petugas" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm" placeholder="Catatan..."></textarea>
                            <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                Terima Pengembalian
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- ACTION: DI DENDA -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'di_denda')
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Status: Di Denda</h4>
                    <div class="mb-4 rounded-md bg-orange-50 border-l-4 border-orange-500 p-4">
                        <p class="text-sm text-orange-800">Menunggu user membayar denda <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong></p>
                    </div>
                    @if($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                        <div class="mb-4 rounded-md bg-yellow-50 border-l-4 border-yellow-500 p-4">
                            <p class="text-sm font-medium text-yellow-800">Peminjam sudah upload bukti. Verifikasi untuk menyelesaikan peminjaman.</p>
                        </div>
                        <div class="flex space-x-3">
                            <form action="{{ route('petugas.peminjaman.verifikasi-pembayaran', $peminjaman) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                    Verifikasi Pembayaran
                                </button>
                            </form>
                            <button onclick="document.getElementById('tolakPembayaranModal').classList.remove('hidden')"
                                    class="flex-1 rounded-md bg-red-600 px-4 py-3 text-sm font-medium text-white hover:bg-red-700">
                                Tolak Pembayaran
                            </button>
                        </div>
                    @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                        <div class="mb-4 rounded-md bg-green-50 border-l-4 border-green-500 p-4">
                            <p class="text-sm font-medium text-green-800">Pembayaran terverifikasi. Selesaikan peminjaman:</p>
                        </div>
                        <form action="{{ route('petugas.peminjaman.selesaikan-denda', $peminjaman) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                Selesaikan Peminjaman (Status: Dikembalikan)
                            </button>
                        </form>
                    @else
                        <div class="rounded-md bg-gray-50 border-l-4 border-gray-400 p-4">
                            <p class="text-sm text-gray-600">Menunggu user upload bukti pembayaran...</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="flex justify-between">
                <a href="{{ route('petugas.peminjaman.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- MODAL: Reject -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium">Tolak Peminjaman</h3>
                <form action="{{ route('petugas.peminjaman.reject', $peminjaman) }}" method="POST">
                    @csrf @method('PATCH')
                    <textarea name="alasan_penolakan" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Alasan penolakan..."></textarea>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">Batal</button>
                        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Tolak Pembayaran -->
    <div id="tolakPembayaranModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium">Tolak Pembayaran Denda</h3>
                <form action="{{ route('petugas.peminjaman.tolak-pembayaran', $peminjaman) }}" method="POST">
                    @csrf @method('PATCH')
                    <textarea name="catatan_petugas" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Bukti tidak jelas, jumlah tidak sesuai..."></textarea>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('tolakPembayaranModal').classList.add('hidden')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">Batal</button>
                        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Kembalikan Denda (dari pengajuan_pengembalian) -->
    <div id="kembalikanDendaModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium">Kembalikan Alat — Status "Di Denda"</h3>
                <div class="mb-4 rounded-md bg-orange-50 p-4">
                    <p class="text-sm text-orange-800">Denda: <strong>Rp {{ number_format($peminjaman->denda ?? 0, 0, ',', '.') }}</strong></p>
                </div>
                <form action="{{ route('petugas.peminjaman.kembalikan-denda', $peminjaman) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                        <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih</option>
                            <option value="baik">Baik</option><option value="rusak">Rusak</option><option value="hilang">Hilang</option>
                        </select>
                    </div>
                    <textarea name="catatan_petugas" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm mb-4" placeholder="Catatan..."></textarea>
                    <div class="space-y-2">
                        <button type="submit" class="w-full rounded-md bg-orange-600 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-700">Kembalikan & Set "Di Denda"</button>
                        <button type="button" onclick="document.getElementById('kembalikanDendaModal').classList.add('hidden')" class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Kembalikan Paksa (dari dipinjam) -->
    <div id="kembalikanDendaDariDipinjamModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium">Proses Pengembalian Langsung</h3>
                <div class="mb-4 rounded-md bg-orange-50 p-4">
                    <p class="text-sm text-orange-800">
                        Estimasi denda: <strong>Rp {{ number_format($estDenda ?? 0, 0, ',', '.') }}</strong>
                        ({{ $hariLambat ?? 0 }} hari × Rp 5.000)
                    </p>
                </div>
                <form action="{{ route('petugas.peminjaman.kembalikan-denda-paksa', $peminjaman) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kondisi Alat <span class="text-red-500">*</span></label>
                        <select name="kondisi_alat" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih</option>
                            <option value="baik">Baik</option><option value="rusak">Rusak</option><option value="hilang">Hilang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Kembali Aktual</label>
                        <input type="date" name="tanggal_kembali_actual" value="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <textarea name="catatan_petugas" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm mb-4" placeholder="Catatan..."></textarea>
                    <div class="space-y-2">
                        <button type="submit" class="w-full rounded-md bg-orange-600 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-700">Proses & Set "Di Denda"</button>
                        <button type="button" onclick="document.getElementById('kembalikanDendaDariDipinjamModal').classList.add('hidden')" class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-petugas-layout>