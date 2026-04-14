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

            {{-- ── INFO PEMINJAMAN ── --}}
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
                                <p class="text-xs text-red-600 mt-1">
                                    {{ $peminjaman->jumlah_hari_terlambat }} hari × Rp {{ number_format($pengaturan->tarif_per_hari, 0, ',', '.') }}
                                </p>
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

            {{-- ── DAFTAR ALAT ── --}}
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

            {{-- ── FOTO PENGEMBALIAN ── --}}
            @if($peminjaman->foto_pengembalian)
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 mb-3">Foto Pengembalian</p>
                    <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}"
                         alt="Foto Pengembalian" class="max-w-md rounded-lg border shadow-sm">
                </div>
            </div>
            @endif

            {{-- ── FORM AJUKAN PENGEMBALIAN ── --}}
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
                            <strong>Denda:</strong> Rp {{ number_format($pengaturan->tarif_per_hari, 0, ',', '.') }}/hari jika terlambat
                        </div>
                        <button type="submit"
                                class="w-full rounded-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700">
                            Ajukan Pengembalian
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- ══ UPLOAD BUKTI PEMBAYARAN ══ --}}
            @if(in_array($peminjaman->status, ['di_denda','pengajuan_pengembalian']) && $peminjaman->denda > 0)

                {{-- STATUS: Belum bayar → tampilkan form + info rekening --}}
                @if(in_array($peminjaman->status_pembayaran_denda, ['belum_bayar', null]))
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">

                    {{-- Header merah --}}
                    <div class="border-b border-red-100 bg-gradient-to-r from-red-50 to-orange-50 px-6 py-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-red-800">Denda Keterlambatan</h4>
                                <p class="text-xs text-red-500">Selesaikan pembayaran untuk melanjutkan proses pengembalian</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-red-700">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                                @if($peminjaman->jumlah_hari_terlambat > 0)
                                <p class="text-xs text-red-500">
                                    {{ $peminjaman->jumlah_hari_terlambat }} hari × Rp {{ number_format($pengaturan->tarif_per_hari, 0, ',', '.') }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- ── PANEL INFO REKENING (dari database) ── --}}
                        <div class="rounded-xl border-2 border-blue-200 bg-blue-50 p-4">
                            <div class="mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <p class="text-sm font-bold text-blue-800">Informasi Rekening Pembayaran</p>
                            </div>

                            {{-- Grid: Bank + Dana (jika ada) --}}
                            <div class="grid grid-cols-1 gap-3 {{ $pengaturan->no_dana ? 'sm:grid-cols-2' : '' }}">

                                {{-- Rekening Bank --}}
                                <div class="rounded-lg bg-white border border-blue-100 p-3">
                                    <div class="mb-2 flex items-center gap-2">
                                        <span class="inline-flex h-6 items-center justify-center rounded-md bg-blue-600 px-2 text-xs font-bold text-white">
                                            {{ strtoupper($pengaturan->nama_bank) }}
                                        </span>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Transfer Bank</p>
                                    </div>
                                    <p class="text-base font-bold tracking-widest text-gray-900">{{ $pengaturan->no_rekening }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">
                                        a.n. <span class="font-medium text-gray-700">{{ $pengaturan->atas_nama }}</span>
                                    </p>
                                </div>

                                {{-- Dana (hanya jika diisi admin) --}}
                                @if($pengaturan->no_dana)
                                <div class="rounded-lg bg-white border border-blue-100 p-3">
                                    <div class="mb-2 flex items-center gap-2">
                                        <span class="inline-flex h-6 items-center justify-center rounded-md bg-blue-500 px-2 text-xs font-bold text-white">DANA</span>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Dana</p>
                                    </div>
                                    <p class="text-base font-bold tracking-widest text-gray-900">{{ $pengaturan->no_dana }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">
                                        a.n. <span class="font-medium text-gray-700">{{ $pengaturan->nama_dana ?? $pengaturan->atas_nama }}</span>
                                    </p>
                                </div>
                                @endif
                            </div>

                            <p class="mt-3 text-xs text-blue-600">
                                ⚠️ Pastikan nominal transfer tepat
                                <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong>
                                · Simpan bukti sebelum upload.
                            </p>
                        </div>

                        {{-- ── FORM UPLOAD BUKTI ── --}}
                        <div>
                            <h4 class="mb-3 text-sm font-semibold text-gray-900">Upload Bukti Pembayaran</h4>
                            <form action="{{ route('peminjaman.upload-bukti-pembayaran', $peminjaman) }}" method="POST"
                                  enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                {{-- Drop area --}}
                                <div class="relative flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center transition-colors hover:border-green-400 hover:bg-green-50"
                                     onclick="document.getElementById('bukti_pembayaran_input').click()">
                                    <svg class="mb-2 h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600">Klik untuk pilih foto bukti transfer</p>
                                    <p class="mt-1 text-xs text-gray-400">JPG, PNG · Maks 2MB</p>
                                    <input type="file"
                                           id="bukti_pembayaran_input"
                                           name="bukti_pembayaran"
                                           accept="image/jpeg,image/png,image/jpg"
                                           required
                                           class="hidden"
                                           onchange="previewBukti(this)">
                                </div>

                                {{-- Preview --}}
                                <div id="preview-bukti" class="hidden">
                                    <p class="mb-1 text-xs font-medium text-gray-500">Preview:</p>
                                    <img id="preview-bukti-img" src="" alt="Preview"
                                         class="max-w-xs rounded-lg border shadow-sm">
                                </div>

                                <button type="submit"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-green-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Upload Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- STATUS: Menunggu verifikasi --}}
                @elseif($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="border-b border-yellow-100 bg-yellow-50 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-100">
                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-yellow-800">Menunggu Verifikasi Petugas</p>
                                <p class="text-xs text-yellow-600">Bukti pembayaran Anda sedang diperiksa, harap tunggu.</p>
                            </div>
                        </div>
                    </div>
                    @if($peminjaman->bukti_pembayaran_denda)
                    <div class="p-6">
                        <p class="mb-3 text-sm font-medium text-gray-700">Bukti yang diupload:</p>
                        <img src="{{ asset('storage/' . $peminjaman->bukti_pembayaran_denda) }}"
                             alt="Bukti Pembayaran" class="max-w-sm rounded-xl border shadow-sm">
                    </div>
                    @endif
                </div>

                {{-- STATUS: Terverifikasi --}}
                @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-green-800">Pembayaran Terverifikasi</p>
                                <p class="text-xs text-green-600">Denda telah lunas. Terima kasih!</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            @endif
            {{-- end upload bukti --}}

            {{-- ── CATATAN ADMIN ── --}}
            @if($peminjaman->catatan_admin_pembayaran)
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="rounded-md border-l-4 border-yellow-500 bg-yellow-50 p-4">
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
        function previewBukti(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-bukti').classList.remove('hidden');
                    document.getElementById('preview-bukti-img').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush
</x-app-layout>