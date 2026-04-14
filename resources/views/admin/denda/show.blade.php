<x-admin-layout>
    <x-slot name="title">Detail Denda #{{ $peminjaman->id }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Denda — Peminjaman #{{ $peminjaman->id }}
            </h2>
            <a href="{{ route('admin.denda.index') }}"
               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-6">

            {{-- FLASH --}}
            @foreach(['success'=>'green','error'=>'red','warning'=>'yellow'] as $type=>$color)
                @if(session($type))
                <div class="rounded-md border-l-4 border-{{ $color }}-500 bg-{{ $color }}-50 p-4">
                    <p class="text-sm font-medium text-{{ $color }}-800">{{ session($type) }}</p>
                </div>
                @endif
            @endforeach

            {{-- ── INFO PEMINJAM ── --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Informasi Peminjam</h3>
                </div>
                <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-3">
                    <div>
                        <p class="text-xs text-gray-400">Nama</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ optional($peminjaman->user)->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Email</p>
                        <p class="mt-1 text-sm text-gray-900">{{ optional($peminjaman->user)->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Status Peminjaman</p>
                        @php
                            $sb = match($peminjaman->status) {
                                'di_denda'   => ['red',  'Di Denda'],
                                'dikembalikan' => ['green','Dikembalikan'],
                                'pengajuan_pengembalian' => ['orange','Pengajuan Kembali'],
                                default      => ['gray', ucfirst($peminjaman->status)],
                            };
                        @endphp
                        <span class="mt-1 inline-flex rounded-full bg-{{ $sb[0] }}-100 px-2.5 py-0.5 text-xs font-semibold text-{{ $sb[0] }}-800">
                            {{ $sb[1] }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── RINGKASAN DENDA ── --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Ringkasan Denda</h3>
                </div>
                <div class="grid grid-cols-2 gap-6 p-6 sm:grid-cols-4">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs text-gray-500">Tgl Rencana Kembali</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $peminjaman->tanggal_kembali?->format('d M Y') ?? '-' }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs text-gray-500">Tgl Kembali Aktual</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $peminjaman->tanggal_kembali_actual?->format('d M Y') ?? '-' }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-red-50 p-4">
                        <p class="text-xs text-red-500">Hari Terlambat</p>
                        <p class="mt-1 text-2xl font-bold text-red-700">{{ $peminjaman->jumlah_hari_terlambat }}</p>
                        <p class="text-xs text-red-400">hari</p>
                    </div>
                    <div class="rounded-lg bg-red-50 p-4">
                        <p class="text-xs text-red-500">Total Denda</p>
                        <p class="mt-1 text-2xl font-bold text-red-700">
                            Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-red-400">@ Rp 5.000/hari</p>
                    </div>
                </div>

                {{-- Status Pembayaran --}}
                <div class="border-t border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Status Pembayaran Denda</p>
                            @php
                                $pb = match($peminjaman->status_pembayaran_denda) {
                                    'terverifikasi'       => ['green',  'Terverifikasi'],
                                    'menunggu_verifikasi' => ['yellow', 'Menunggu Verifikasi'],
                                    default               => ['red',    'Belum Bayar'],
                                };
                            @endphp
                            <span class="inline-flex rounded-full bg-{{ $pb[0] }}-100 px-3 py-1 text-sm font-semibold text-{{ $pb[0] }}-800">
                                {{ $pb[1] }}
                            </span>
                        </div>
                        @if($peminjaman->kondisi_alat)
                        <div class="text-right">
                            <p class="text-xs text-gray-400 mb-1">Kondisi Alat Kembali</p>
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                                {{ ucfirst($peminjaman->kondisi_alat) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @if($peminjaman->catatan_admin_pembayaran)
                    <div class="mt-4 rounded-md bg-yellow-50 border-l-4 border-yellow-400 p-3">
                        <p class="text-xs font-medium text-yellow-800">Catatan Admin:</p>
                        <p class="text-sm text-yellow-700 mt-1">{{ $peminjaman->catatan_admin_pembayaran }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── DAFTAR ALAT ── --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                        Daftar Alat ({{ $peminjaman->items->count() }} jenis · {{ $peminjaman->items->sum('jumlah') }} unit)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold uppercase text-gray-500">
                                <th class="px-5 py-3 text-left">Nama Alat</th>
                                <th class="px-5 py-3 text-left">Kategori</th>
                                <th class="px-5 py-3 text-right">Jumlah</th>
                                <th class="px-5 py-3 text-center">Kondisi Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($peminjaman->items as $item)
                            <tr>
                                <td class="px-5 py-3 text-sm font-medium text-gray-900">
                                    {{ optional($item->alat)->nama ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-500">
                                    {{ optional($item->alat)->kategori ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-right text-sm text-gray-700">{{ $item->jumlah }} unit</td>
                                <td class="px-5 py-3 text-center text-sm text-gray-700">
                                    {{ $item->kondisi_alat ? ucfirst($item->kondisi_alat) : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── BUKTI PEMBAYARAN ── --}}
            @if($peminjaman->bukti_pembayaran_denda)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Bukti Pembayaran Denda</h3>
                </div>
                <div class="p-6">
                    <img src="{{ asset('storage/' . $peminjaman->bukti_pembayaran_denda) }}"
                         alt="Bukti Pembayaran"
                         class="max-w-md rounded-xl border border-gray-200 shadow-sm">
                </div>
            </div>
            @endif

            {{-- ── FOTO PENGEMBALIAN ── --}}
            @if($peminjaman->foto_pengembalian)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Foto Pengembalian Alat</h3>
                </div>
                <div class="p-6">
                    <img src="{{ asset('storage/' . $peminjaman->foto_pengembalian) }}"
                         alt="Foto Pengembalian"
                         class="max-w-md rounded-xl border border-gray-200 shadow-sm">
                </div>
            </div>
            @endif

            {{-- ── PANEL AKSI ADMIN ── --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Tindakan Admin</h3>
                </div>
                <div class="space-y-6 p-6">

                    {{-- Verifikasi / Tolak (jika menunggu_verifikasi) --}}
                    @if($peminjaman->status_pembayaran_denda === 'menunggu_verifikasi')
                    <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-5">
                        <p class="mb-4 text-sm font-semibold text-yellow-800">Peminjam sudah upload bukti pembayaran — perlu diverifikasi:</p>
                        <div class="flex gap-3">
                            <form action="{{ route('admin.denda.verifikasi', $peminjaman) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                                    Verifikasi Pembayaran
                                </button>
                            </form>
                            <button onclick="document.getElementById('tolakModal').classList.remove('hidden')"
                                    class="flex-1 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                                Tolak Pembayaran
                            </button>
                        </div>
                    </div>
                    @elseif($peminjaman->status_pembayaran_denda === 'terverifikasi')
                    <div class="rounded-lg bg-green-50 border border-green-200 p-4">
                        <p class="text-sm font-medium text-green-800">Pembayaran sudah terverifikasi. Tidak ada tindakan lebih lanjut.</p>
                    </div>
                    @else
                    <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                        <p class="text-sm text-gray-600">Peminjam belum upload bukti pembayaran. Menunggu konfirmasi user.</p>
                    </div>
                    @endif

                    {{-- Ubah Nominal Denda --}}
                    <div class="rounded-lg border border-gray-200 p-5">
                        <p class="mb-3 text-sm font-semibold text-gray-700">Ubah Nominal Denda (Koreksi)</p>
                        <form action="{{ route('admin.denda.ubah', $peminjaman) }}" method="POST" class="space-y-3">
                            @csrf @method('PATCH')
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Nominal Baru (Rp)</label>
                                    <input type="number" name="denda_baru" value="{{ $peminjaman->denda }}" min="0"
                                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Alasan <span class="text-red-500">*</span></label>
                                    <input type="text" name="alasan_ubah" required placeholder="Contoh: Negosiasi, kesalahan hitung..."
                                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                            <button type="submit"
                                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                Simpan Perubahan Denda
                            </button>
                        </form>
                    </div>

                    {{-- Hapus Denda --}}
                    <div class="rounded-lg border border-red-100 bg-red-50 p-5">
                        <p class="mb-3 text-sm font-semibold text-red-800">Hapus / Reset Denda</p>
                        <p class="mb-3 text-xs text-red-600">Gunakan fitur ini hanya jika ada kesalahan sistem atau kebijakan khusus.</p>
                        <button onclick="document.getElementById('hapusDendaModal').classList.remove('hidden')"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Hapus Denda
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- MODAL: Tolak Pembayaran --}}
    <div id="tolakModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-60"></div>
            <div class="relative z-10 w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                <h3 class="mb-4 text-base font-semibold text-gray-900">Tolak Pembayaran Denda</h3>
                <form action="{{ route('admin.denda.tolak', $peminjaman) }}" method="POST" class="space-y-4">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alasan penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan_admin" rows="4" required
                                  placeholder="Contoh: Bukti tidak jelas, nominal tidak sesuai..."
                                  class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button"
                                onclick="document.getElementById('tolakModal').classList.add('hidden')"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Hapus Denda --}}
    <div id="hapusDendaModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-60"></div>
            <div class="relative z-10 w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
                <h3 class="mb-2 text-base font-semibold text-gray-900">Hapus / Reset Denda</h3>
                <p class="mb-4 text-sm text-gray-500">
                    Tindakan ini akan menghapus denda <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong>
                    dari peminjaman ini. Tidak dapat dibatalkan.
                </p>
                <form action="{{ route('admin.denda.hapus', $peminjaman) }}" method="POST" class="space-y-4">
                    @csrf @method('DELETE')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alasan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_hapus" rows="3" required
                                  placeholder="Contoh: Kesalahan sistem, dibebaskan oleh manajemen..."
                                  class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button"
                                onclick="document.getElementById('hapusDendaModal').classList.add('hidden')"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">
                            Batal
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800">
                            Ya, Hapus Denda
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>