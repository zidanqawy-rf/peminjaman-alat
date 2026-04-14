<x-admin-layout>
    <x-slot name="title">Master Data Denda</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Master Data Denda</h2>
            <a href="{{ route('admin.denda.export', request()->query()) }}"
               class="inline-flex items-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- FLASH --}}
        @foreach(['success'=>'green','error'=>'red','warning'=>'yellow'] as $type=>$color)
            @if(session($type))
            <div class="rounded-md border-l-4 border-{{ $color }}-500 bg-{{ $color }}-50 p-4">
                <p class="text-sm font-medium text-{{ $color }}-800">{{ session($type) }}</p>
            </div>
            @endif
        @endforeach

        @if($errors->any())
        <div class="rounded-md border-l-4 border-red-500 bg-red-50 p-4">
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- ══ PANEL PENGATURAN ══ --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-blue-100">
            <div class="flex items-center justify-between border-b border-blue-100 bg-blue-50 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900">Pengaturan Denda & Info Pembayaran</h3>
                        <p class="text-xs text-blue-600">Tarif dan rekening yang diubah langsung tampil ke halaman user</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium uppercase tracking-wide text-blue-500">Tarif Saat Ini</p>
                    <p class="text-2xl font-bold text-blue-700">
                        Rp {{ number_format($pengaturan->tarif_per_hari, 0, ',', '.') }}
                        <span class="text-sm font-normal text-blue-400">/hari</span>
                    </p>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.denda.pengaturan') }}" method="POST">
                    @csrf @method('PATCH')

                    {{-- ── BARIS 1: Tarif + Keterangan ── --}}
                    <div class="mb-5">
                        <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">Tarif Denda</p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tarif per Hari (Rp) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-semibold text-gray-500 pointer-events-none select-none">Rp</span>
                                    <input type="number"
                                           name="tarif_per_hari"
                                           value="{{ old('tarif_per_hari', $pengaturan->tarif_per_hari) }}"
                                           min="0" max="1000000" required
                                           class="w-full rounded-lg border-gray-300 pl-12 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="5000">
                                </div>
                                <p class="mt-1 text-xs text-gray-400">Angka dalam rupiah, tanpa titik/koma</p>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                <input type="text"
                                       name="keterangan"
                                       value="{{ old('keterangan', $pengaturan->keterangan) }}"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Contoh: Tarif denda keterlambatan pengembalian alat">
                            </div>
                        </div>
                    </div>

                    {{-- ── BARIS 2: Info Rekening Bank ── --}}
                    <div class="mb-5 rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <div class="mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Rekening Bank Transfer</p>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Bank <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="nama_bank"
                                       value="{{ old('nama_bank', $pengaturan->nama_bank) }}"
                                       required maxlength="100"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="BCA, BRI, Mandiri, dst.">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nomor Rekening <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="no_rekening"
                                       value="{{ old('no_rekening', $pengaturan->no_rekening) }}"
                                       required maxlength="50"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="1234567890">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Atas Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="atas_nama"
                                       value="{{ old('atas_nama', $pengaturan->atas_nama) }}"
                                       required maxlength="150"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Laboratorium XYZ">
                            </div>
                        </div>
                    </div>

                    {{-- ── BARIS 3: Info DANA (opsional) ── --}}
                    <div class="mb-5 rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <div class="mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Dana / Dompet Digital
                                <span class="ml-1 text-gray-400 font-normal normal-case">(opsional)</span>
                            </p>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Dana</label>
                                <input type="text"
                                       name="no_dana"
                                       value="{{ old('no_dana', $pengaturan->no_dana) }}"
                                       maxlength="20"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="08xx-xxxx-xxxx">
                                <p class="mt-1 text-xs text-gray-400">Kosongkan jika tidak tersedia</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Akun Dana</label>
                                <input type="text"
                                       name="nama_dana"
                                       value="{{ old('nama_dana', $pengaturan->nama_dana) }}"
                                       maxlength="150"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Laboratorium XYZ">
                            </div>
                        </div>
                    </div>

                    {{-- ── Preview live info pembayaran ── --}}
                    <div class="mb-5 rounded-xl border-2 border-dashed border-blue-200 bg-blue-50 p-4">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-blue-500">Preview — Tampilan di halaman user</p>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-lg bg-white border border-blue-100 p-3">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="inline-flex h-6 items-center justify-center rounded-md bg-blue-600 px-2 text-xs font-bold text-white">
                                        {{ strtoupper($pengaturan->nama_bank) }}
                                    </span>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Transfer Bank</p>
                                </div>
                                <p class="text-sm font-bold text-gray-900 tracking-widest">{{ $pengaturan->no_rekening }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">a.n. <span class="font-medium text-gray-700">{{ $pengaturan->atas_nama }}</span></p>
                            </div>
                            @if($pengaturan->no_dana)
                            <div class="rounded-lg bg-white border border-blue-100 p-3">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="inline-flex h-6 items-center justify-center rounded-md bg-blue-500 px-2 text-xs font-bold text-white">DANA</span>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dana</p>
                                </div>
                                <p class="text-sm font-bold text-gray-900 tracking-widest">{{ $pengaturan->no_dana }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">a.n. <span class="font-medium text-gray-700">{{ $pengaturan->nama_dana ?? $pengaturan->atas_nama }}</span></p>
                            </div>
                            @else
                            <div class="rounded-lg bg-white border border-dashed border-gray-200 p-3 flex items-center justify-center">
                                <p class="text-xs text-gray-400">Dana tidak diaktifkan</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Pengaturan
                        </button>
                        <p class="text-xs text-gray-400">
                            Terakhir diperbarui: {{ $pengaturan->updated_at->format('d M Y, H:i') }}
                            @if($pengaturan->keterangan)
                                · {{ $pengaturan->keterangan }}
                            @endif
                        </p>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── STATS CARDS ── --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Kasus Denda</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_denda']) }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Nominal</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-red-100">
                <p class="text-xs font-medium uppercase tracking-wide text-red-500">Belum Lunas</p>
                <p class="mt-2 text-3xl font-bold text-red-700">{{ number_format($stats['belum_bayar']) }}</p>
                <p class="mt-1 text-xs text-red-500">Rp {{ number_format($stats['nominal_belum_lunas'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-yellow-100">
                <p class="text-xs font-medium uppercase tracking-wide text-yellow-600">Menunggu Verifikasi</p>
                <p class="mt-2 text-3xl font-bold text-yellow-700">{{ number_format($stats['menunggu_verifikasi']) }}</p>
            </div>
        </div>

        {{-- ── FILTER ── --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
            <div class="p-5">
                <form method="GET" action="{{ route('admin.denda.index') }}"
                      class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Cari peminjam / alat</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama, email, atau alat..."
                               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status Pembayaran</label>
                        <select name="status_pembayaran" class="w-full rounded-lg border-gray-300 text-sm shadow-sm">
                            <option value="">Semua</option>
                            <option value="belum_bayar"         @selected(request('status_pembayaran')==='belum_bayar')>Belum Bayar</option>
                            <option value="menunggu_verifikasi" @selected(request('status_pembayaran')==='menunggu_verifikasi')>Menunggu Verifikasi</option>
                            <option value="terverifikasi"       @selected(request('status_pembayaran')==='terverifikasi')>Terverifikasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status Peminjaman</label>
                        <select name="status_peminjaman" class="w-full rounded-lg border-gray-300 text-sm shadow-sm">
                            <option value="">Semua</option>
                            <option value="di_denda"               @selected(request('status_peminjaman')==='di_denda')>Di Denda</option>
                            <option value="dikembalikan"           @selected(request('status_peminjaman')==='dikembalikan')>Dikembalikan</option>
                            <option value="pengajuan_pengembalian" @selected(request('status_peminjaman')==='pengajuan_pengembalian')>Pengajuan Kembali</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Filter</button>
                        <a href="{{ route('admin.denda.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── TABLE ── --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-5 py-3 text-left">ID</th>
                            <th class="px-5 py-3 text-left">Peminjam</th>
                            <th class="px-5 py-3 text-left">Alat</th>
                            <th class="px-5 py-3 text-left">Tgl Rencana Kembali</th>
                            <th class="px-5 py-3 text-left">Tgl Kembali Aktual</th>
                            <th class="px-5 py-3 text-right">Hari Terlambat</th>
                            <th class="px-5 py-3 text-right">Nominal Denda</th>
                            <th class="px-5 py-3 text-center">Status Bayar</th>
                            <th class="px-5 py-3 text-center">Status Pinjam</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dendas as $d)
                        @php
                            $bayarBadge = match($d->status_pembayaran_denda) {
                                'terverifikasi'       => ['green',  'Terverifikasi'],
                                'menunggu_verifikasi' => ['yellow', 'Menunggu Verif.'],
                                default               => ['red',    'Belum Bayar'],
                            };
                            $pinjamBadge = match($d->status) {
                                'di_denda'               => ['red',    'Di Denda'],
                                'dikembalikan'           => ['green',  'Dikembalikan'],
                                'pengajuan_pengembalian' => ['orange', 'Pengajuan Kembali'],
                                default                  => ['gray',   ucfirst($d->status)],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-sm font-medium text-gray-700">#{{ $d->id }}</td>
                            <td class="px-5 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ optional($d->user)->name }}</p>
                                <p class="text-xs text-gray-400">{{ optional($d->user)->email }}</p>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $d->nama_alat_singkat }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $d->tanggal_kembali?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $d->tanggal_kembali_actual?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-3 text-right">
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800">
                                    {{ $d->jumlah_hari_terlambat }} hari
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right text-sm font-semibold text-gray-900">
                                Rp {{ number_format($d->denda, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex rounded-full bg-{{ $bayarBadge[0] }}-100 px-2.5 py-0.5 text-xs font-semibold text-{{ $bayarBadge[0] }}-800">
                                    {{ $bayarBadge[1] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex rounded-full bg-{{ $pinjamBadge[0] }}-100 px-2.5 py-0.5 text-xs font-semibold text-{{ $pinjamBadge[0] }}-800">
                                    {{ $pinjamBadge[1] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('admin.denda.show', $d) }}"
                                   class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center text-sm text-gray-400">
                                Tidak ada data denda yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dendas->hasPages())
            <div class="border-t border-gray-100 px-5 py-4">
                {{ $dendas->links() }}
            </div>
            @endif
        </div>

    </div>
</x-admin-layout>