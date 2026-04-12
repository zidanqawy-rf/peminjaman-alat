<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detail Peminjaman #{{ $peminjaman->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

            @foreach(['success'=>'green','warning'=>'yellow','error'=>'red'] as $type=>$color)
                @if(session($type))
                <div class="mb-6 border-l-4 border-{{ $color }}-500 bg-{{ $color }}-50 p-4 rounded-md">
                    <p class="text-sm text-{{ $color }}-700">{{ session($type) }}</p>
                </div>
                @endif
            @endforeach

            @if($errors->any())
            <div class="mb-6 border-l-4 border-red-500 bg-red-50 p-4 rounded-md">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <!-- Info Peminjaman -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Peminjaman</h3>
                        @php
                            $badges=['menunggu'=>['yellow','Menunggu'],'disetujui'=>['blue','Disetujui'],'dipinjam'=>['purple','Dipinjam'],
                                     'pengajuan_pengembalian'=>['orange','Pengajuan Kembali'],'di_denda'=>['red','Di Denda'],
                                     'dikembalikan'=>['green','Dikembalikan'],'ditolak'=>['red','Ditolak'],'dibatalkan'=>['gray','Dibatalkan']];
                            [$bc,$bl] = $badges[$peminjaman->status] ?? ['gray',ucfirst($peminjaman->status)];
                        @endphp
                        <span class="inline-flex rounded-full bg-{{ $bc }}-100 px-3 py-1 text-xs font-semibold text-{{ $bc }}-800">
                            {{ $bl }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
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
                            <div class="rounded-lg bg-red-50 border-2 border-red-200 p-4">
                                <p class="text-sm font-medium text-red-800 mb-1">⚠️ Denda Keterlambatan</p>
                                <p class="text-2xl font-bold text-red-700">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                                @if($peminjaman->jumlah_hari_terlambat > 0)
                                <p class="text-xs text-red-600 mt-1">{{ $peminjaman->jumlah_hari_terlambat }} hari × Rp 5.000</p>
                                @endif
                                @if($peminjaman->status_pembayaran_denda === 'terverifikasi')
                                    <span class="mt-2 inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">✓ Terverifikasi</span>
                                @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                                    <span class="mt-2 inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">⏳ Menunggu Verifikasi</span>
                                @else
                                    <span class="mt-2 inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Belum Dibayar</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- DAFTAR ALAT -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">
                        Daftar Alat Dipinjam ({{ $peminjaman->items->count() }} jenis)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Alat</th>
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

            <!-- Foto Pengembalian -->
            @if($peminjaman->foto_pengembalian)
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 mb-3">Foto Pengembalian</p>
                    <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}"
                         alt="Foto Pengembalian" class="max-w-md rounded-lg border shadow-sm">
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- FORM AJUKAN PENGEMBALIAN (status: dipinjam) -->
            <!-- ============================================================ -->
            @if($peminjaman->status === 'dipinjam')
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="mb-4 text-md font-semibold text-gray-900">Ajukan Pengembalian Alat</h4>
                    <form action="{{ route('peminjaman.ajukan-pengembalian', $peminjaman) }}" method="POST"
                          enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Tanggal Pengembalian Aktual <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_kembali_actual"
                                   value="{{ old('tanggal_kembali_actual', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Foto Dokumentasi <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="foto_pengembalian" accept="image/jpeg,image/png,image/jpg" required
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG · Maks 2MB</p>
                        </div>
                        <div class="rounded-md bg-blue-50 p-4 text-sm text-blue-700">
                            <strong>Denda:</strong> Rp 5.000/hari jika terlambat
                        </div>
                        <button type="submit"
                                class="w-full rounded-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700">
                            Ajukan Pengembalian
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- ============================================================ -->
            <!-- UPLOAD BUKTI PEMBAYARAN (status: di_denda atau pengajuan_pengembalian dengan denda) -->
            <!-- ============================================================ -->
            @if(in_array($peminjaman->status, ['di_denda','pengajuan_pengembalian']) && $peminjaman->denda > 0)
                @if(in_array($peminjaman->status_pembayaran_denda, ['belum_bayar', null]))
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="mb-4 rounded-md bg-red-50 border-l-4 border-red-500 p-4">
                            <p class="text-sm font-medium text-red-800">🔴 Harap bayar denda:
                                <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong>
                            </p>
                        </div>
                        <h4 class="mb-4 text-md font-semibold text-gray-900">Upload Bukti Pembayaran</h4>
                        <form action="{{ route('peminjaman.upload-bukti-pembayaran', $peminjaman) }}" method="POST"
                              enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Bukti Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg" required
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-green-700 hover:file:bg-green-100">
                            </div>
                            <div class="rounded-md bg-blue-50 p-4 text-sm text-blue-700">
                                <p><strong>Bank:</strong> BCA · <strong>No. Rek:</strong> 1234567890 · <strong>A/N:</strong> Laboratorium XYZ</p>
                            </div>
                            <button type="submit"
                                    class="w-full rounded-md bg-green-600 px-4 py-3 text-sm font-medium text-white hover:bg-green-700">
                                Upload Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
                @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="rounded-md bg-yellow-50 border-l-4 border-yellow-500 p-4">
                            <p class="text-sm font-medium text-yellow-800">⏳ Bukti pembayaran sedang diverifikasi petugas...</p>
                        </div>
                        @if($peminjaman->bukti_pembayaran_denda)
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Bukti yang diupload:</p>
                            <img src="{{ asset('storage/' . $peminjaman->bukti_pembayaran_denda) }}"
                                 alt="Bukti" class="max-w-md rounded-lg border shadow-sm">
                        </div>
                        @endif
                    </div>
                </div>
                @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="rounded-md bg-green-50 border-l-4 border-green-500 p-4">
                            <p class="text-sm font-medium text-green-800">✓ Pembayaran denda telah diverifikasi.</p>
                        </div>
                    </div>
                </div>
                @endif
            @endif

            @if($peminjaman->catatan_admin_pembayaran)
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="rounded-md bg-yellow-50 border-l-4 border-yellow-500 p-4">
                        <p class="text-sm font-medium text-yellow-800">Catatan Petugas:</p>
                        <p class="mt-1 text-sm text-yellow-700">{{ $peminjaman->catatan_admin_pembayaran }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div>
                <a href="{{ route('peminjaman.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('foto_pengembalian')?.addEventListener('change', function(){
            previewImg(this, 'preview-foto');
        });
        document.getElementById('bukti_pembayaran')?.addEventListener('change', function(){
            previewImg(this, 'preview-bukti');
        });
        function previewImg(input, id){
            var f=input.files[0], el=document.getElementById(id);
            if(f&&el){var r=new FileReader();r.onload=function(e){el.innerHTML='<img src="'+e.target.result+'" class="max-w-xs rounded-md border mt-2">';};r.readAsDataURL(f);}
        }
    </script>
    @endpush
</x-app-layout>