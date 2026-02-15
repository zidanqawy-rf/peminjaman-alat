<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Form Peminjaman Alat
        </h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap');

        #pm-wrap, #pm-wrap * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        .pm-card { background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(0,0,0,.07),0 4px 16px rgba(0,0,0,.05); border:1px solid #e2e8f0; }

        /* STEP */
        .pm-steps { display:flex; align-items:center; }
        .pm-step  { display:flex; align-items:center; gap:10px; }
        .pm-line  { flex:1; height:2px; background:#e2e8f0; margin:0 12px; }
        .pm-circle-on  { width:38px;height:38px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;box-shadow:0 0 0 4px rgba(37,99,235,.2);flex-shrink:0; }
        .pm-circle-off { width:38px;height:38px;border-radius:50%;background:#e2e8f0;color:#94a3b8;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0; }

        /* SECTION TITLE */
        .pm-sec { font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.07em;display:flex;align-items:center;gap:8px;margin-bottom:16px; }
        .pm-sec::before { content:'';display:block;width:3px;height:16px;background:#2563eb;border-radius:2px; }

        /* ALAT SELECTOR PANEL */
        .pm-selector { border:2px solid #e2e8f0;border-radius:14px;overflow:hidden; }
        .pm-selector-head { background:#f8fafc;padding:14px 16px;border-bottom:1px solid #e2e8f0; display:flex;align-items:center;gap:12px; }

        /* SEARCH INPUT */
        .pm-search-wrap { position:relative;flex:1; }
        .pm-search-wrap svg { position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8;pointer-events:none; }
        .pm-search-wrap input { width:100%;padding:9px 12px 9px 36px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:13px;outline:none;background:#fff;transition:border-color .2s; }
        .pm-search-wrap input:focus { border-color:#2563eb; }

        /* FILTER PILLS */
        .pm-pills { display:flex;gap:6px;flex-wrap:wrap;padding:12px 16px;border-bottom:1px solid #e2e8f0;background:#fafafa; }
        .pm-pill { padding:5px 12px;border-radius:20px;font-size:12px;font-weight:500;border:1.5px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;user-select:none;line-height:1; }
        .pm-pill:hover { border-color:#2563eb;color:#2563eb; }
        .pm-pill.on   { background:#2563eb;border-color:#2563eb;color:#fff; }

        /* ALAT LIST */
        .pm-list { max-height:320px;overflow-y:auto; }
        .pm-list::-webkit-scrollbar { width:5px; }
        .pm-list::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:4px; }

        .pm-list-item {
            display:flex;align-items:center;gap:12px;
            padding:12px 16px;border-bottom:1px solid #f1f5f9;
            transition:background .15s;
        }
        .pm-list-item:last-child { border-bottom:none; }
        .pm-list-item:hover { background:#f8fafc; }
        .pm-list-item.added { background:#eff6ff; }

        .pm-item-img { width:52px;height:52px;border-radius:10px;object-fit:cover;flex-shrink:0;background:#e2e8f0; }
        .pm-item-img-placeholder { width:52px;height:52px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,#dbeafe,#bfdbfe);display:flex;align-items:center;justify-content:center;font-size:22px; }

        .pm-item-info { flex:1;min-width:0; }
        .pm-item-name { font-weight:600;font-size:14px;color:#0f172a;line-height:1.3; }
        .pm-item-meta { font-size:12px;color:#64748b;margin-top:2px; }
        .pm-item-stok { display:inline-flex;align-items:center;font-size:11px;font-weight:600;padding:2px 7px;border-radius:6px;margin-top:4px; }
        .pm-stok-ok  { background:#dcfce7;color:#166534; }
        .pm-stok-low { background:#fef9c3;color:#854d0e; }
        .pm-stok-out { background:#fee2e2;color:#991b1b; }

        /* TOMBOL TAMBAH */
        .pm-add-btn {
            padding:8px 16px;border-radius:10px;border:none;
            font-family:inherit;font-size:13px;font-weight:600;
            cursor:pointer;transition:all .15s;flex-shrink:0;
        }
        .pm-add-btn-add    { background:#2563eb;color:#fff; }
        .pm-add-btn-add:hover { background:#1e40af; }
        .pm-add-btn-remove { background:#fee2e2;color:#ef4444; }
        .pm-add-btn-remove:hover { background:#fecaca; }
        .pm-add-btn-dis    { background:#f1f5f9;color:#94a3b8;cursor:not-allowed; }

        .pm-no-result { text-align:center;padding:24px;color:#94a3b8;font-size:13px; }

        /* KERANJANG */
        .pm-cart-sticky { position:sticky;top:16px; }
        .pm-cart-hdr { display:flex;align-items:center;justify-content:space-between;margin-bottom:12px; }
        .pm-cart-badge { background:#2563eb;color:#fff;font-size:11px;font-weight:700;border-radius:20px;padding:2px 9px;min-width:28px;text-align:center; }
        .pm-cart-empty { text-align:center;padding:26px 16px;color:#94a3b8; }

        .pm-cart-item { display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:#f8fafc;margin-bottom:7px;border:1px solid #e2e8f0; }
        .pm-ci-img { width:34px;height:34px;border-radius:7px;object-fit:cover;flex-shrink:0;background:#e2e8f0; }
        .pm-ci-img-ph { width:34px;height:34px;border-radius:7px;flex-shrink:0;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:14px; }
        .pm-ci-info { flex:1;min-width:0; }
        .pm-ci-name { font-size:12px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }

        /* QTY CONTROL DI KERANJANG */
        .pm-ci-qty-wrap { display:flex;align-items:center;gap:5px;flex-shrink:0; }
        .pm-ci-qbtn {
            width:24px;height:24px;border-radius:6px;border:none;
            display:flex;align-items:center;justify-content:center;
            font-size:14px;font-weight:700;cursor:pointer;
            flex-shrink:0;
        }
        .pm-ci-qbtn-m { background:#e2e8f0;color:#374151; }
        .pm-ci-qbtn-m:hover { background:#cbd5e1; }
        .pm-ci-qbtn-p { background:#2563eb;color:#fff; }
        .pm-ci-qbtn-p:hover { background:#1e40af; }
        .pm-ci-qty-num { font-family:'JetBrains Mono',monospace;font-weight:700;font-size:14px;color:#2563eb;min-width:20px;text-align:center; }

        .pm-ci-rm { background:none;border:none;cursor:pointer;color:#cbd5e1;font-size:14px;padding:2px;line-height:1; }
        .pm-ci-rm:hover { color:#ef4444; }

        .pm-cart-total { border-top:1px dashed #e2e8f0;margin-top:10px;padding-top:10px;display:flex;justify-content:space-between;align-items:center; }
        .pm-cart-clear { width:100%;margin-top:9px;padding:7px;background:none;border:1.5px solid #fee2e2;border-radius:10px;font-family:inherit;font-size:12px;font-weight:600;color:#ef4444;cursor:pointer; }
        .pm-cart-clear:hover { background:#fff5f5; }

        /* FORM */
        .pm-label { font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:5px; }
        .pm-input { width:100%;padding:10px 14px;border:2px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:14px;color:#0f172a;outline:none;transition:border-color .2s; }
        .pm-input:focus { border-color:#2563eb; }
        .pm-upload { border:2px dashed #cbd5e1;border-radius:12px;padding:20px 16px;text-align:center;cursor:pointer;background:#fafafa;transition:all .2s; }
        .pm-upload:hover { border-color:#2563eb;background:#eff6ff; }
        .pm-upload.done { border-color:#10b981;background:#f0fdf4; }
        .pm-info { background:#eff6ff;border-left:4px solid #2563eb;border-radius:0 10px 10px 0;padding:12px 16px;margin-top:14px; }

        /* BUTTONS */
        .pm-btn-submit { display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:#2563eb;color:#fff;border:none;border-radius:12px;font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;transition:all .2s; }
        .pm-btn-submit:hover:not([disabled]) { background:#1e40af;transform:translateY(-1px); }
        .pm-btn-submit[disabled] { background:#94a3b8;cursor:not-allowed; }
        .pm-btn-cancel { display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#fff;color:#64748b;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s; }
        .pm-btn-cancel:hover { background:#f8fafc;border-color:#94a3b8; }

        .pm-dur-num { font-family:'JetBrains Mono',monospace;font-size:32px;font-weight:700;color:#2563eb;display:block;text-align:center;padding:10px 0 4px; }
    </style>

    <div id="pm-wrap" class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            {{-- STEP --}}
            <div class="pm-card mb-6 p-6">
                <div class="pm-steps">
                    <div class="pm-step">
                        <div class="pm-circle-on">1</div>
                        <span style="font-size:13px;font-weight:600;color:#2563eb;">Pilih Alat</span>
                    </div>
                    <div class="pm-line"></div>
                    <div class="pm-step">
                        <div class="pm-circle-off">2</div>
                        <span style="font-size:13px;font-weight:500;color:#94a3b8;">Detail Peminjaman</span>
                    </div>
                    <div class="pm-line"></div>
                    <div class="pm-step">
                        <div class="pm-circle-off">3</div>
                        <span style="font-size:13px;font-weight:500;color:#94a3b8;">Konfirmasi</span>
                    </div>
                </div>
            </div>

            {{-- ERRORS --}}
            @if ($errors->any())
            <div class="pm-card mb-5 p-4" style="border-left:4px solid #ef4444;background:#fff5f5;">
                <p style="font-weight:600;font-size:14px;color:#991b1b;margin-bottom:6px">Terdapat kesalahan:</p>
                <ul style="list-style:disc;padding-left:18px;font-size:13px;color:#b91c1c;">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            {{-- FORM --}}
            <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tool_id" id="h_tool_id">
                <input type="hidden" name="jumlah"  id="h_jumlah">

                <div style="display:grid;grid-template-columns:1fr 310px;gap:18px;align-items:start;">

                    {{-- KIRI --}}
                    <div style="display:flex;flex-direction:column;gap:16px;">

                        {{-- KATALOG --}}
                        <div class="pm-card p-6">
                            <div class="pm-sec">Pilih Alat</div>

                            <div class="pm-selector">
                                {{-- HEAD: search + filter --}}
                                <div class="pm-selector-head">
                                    <div class="pm-search-wrap">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <input type="text" id="pmSearch" placeholder="Cari nama alat..." autocomplete="off">
                                    </div>
                                </div>

                                {{-- PILLS --}}
                                @php $cats = $alat->pluck('kategori')->filter()->unique()->values(); @endphp
                                @if($cats->count() > 0)
                                <div class="pm-pills" id="pmPills">
                                    <span class="pm-pill on" data-cat="all">Semua</span>
                                    @foreach($cats as $cat)
                                        <span class="pm-pill" data-cat="{{ $cat }}">{{ ucfirst($cat) }}</span>
                                    @endforeach
                                </div>
                                @endif

                                {{-- LIST ALAT --}}
                                <div class="pm-list" id="pmList">
                                    @foreach($alat as $item)
                                    @php
                                        $avail = $item->jumlah > 0 && $item->status === 'tersedia';
                                        $sc = $item->jumlah > 5 ? 'pm-stok-ok' : ($item->jumlah > 0 ? 'pm-stok-low' : 'pm-stok-out');
                                        $catName = is_string($item->kategori) ? $item->kategori : ($item->kategori?->nama ?? '');
                                        $map=['laptop'=>'💻','komputer'=>'🖥️','proyektor'=>'📽️',
                                              'kamera'=>'📷','tripod'=>'🎬','mic'=>'🎙️',
                                              'speaker'=>'🔊','drone'=>'🚁','printer'=>'🖨️',
                                              'tablet'=>'📱','monitor'=>'🖥️','keyboard'=>'⌨️'];
                                        $ic='🔧';
                                        foreach($map as $k=>$v){if(str_contains(strtolower($item->nama),$k)){$ic=$v;break;}}
                                    @endphp
                                    <div class="pm-list-item"
                                         data-id="{{ $item->id }}"
                                         data-nama="{{ $item->nama }}"
                                         data-stok="{{ $item->jumlah }}"
                                         data-cat="{{ $catName }}"
                                         data-icon="{{ $ic }}"
                                         data-img="{{ $item->gambar ? asset('storage/'.$item->gambar) : '' }}"
                                         data-avail="{{ $avail ? '1' : '0' }}">

                                        {{-- Gambar --}}
                                        @if($item->gambar)
                                            <img class="pm-item-img"
                                                 src="{{ asset('storage/'.$item->gambar) }}"
                                                 alt="{{ $item->nama }}"
                                                 loading="lazy"
                                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                            <div class="pm-item-img-placeholder" style="display:none;">{{ $ic }}</div>
                                        @else
                                            <div class="pm-item-img-placeholder">{{ $ic }}</div>
                                        @endif

                                        {{-- Info --}}
                                        <div class="pm-item-info">
                                            <div class="pm-item-name">{{ $item->nama }}</div>
                                            @if($catName)
                                                <div class="pm-item-meta">{{ ucfirst($catName) }}</div>
                                            @endif
                                            <span class="pm-item-stok {{ $sc }}">
                                                {{ $avail ? '✓ '.$item->jumlah.' tersedia' : 'Stok habis' }}
                                            </span>
                                        </div>

                                        {{-- Tombol Tambah / Hapus --}}
                                        <button type="button"
                                                id="btn{{ $item->id }}"
                                                class="pm-add-btn {{ $avail ? 'pm-add-btn-add' : 'pm-add-btn-dis' }}"
                                                {{ !$avail ? 'disabled' : '' }}
                                                onclick="toggleItem('{{ $item->id }}')">
                                            + Tambah
                                        </button>
                                    </div>
                                    @endforeach

                                    <div class="pm-no-result" id="pmNoResult" style="display:none;">
                                        🔍 Alat tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- DETAIL --}}
                        <div class="pm-card p-6">
                            <div class="pm-sec">Detail Peminjaman</div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                                <div>
                                    <label class="pm-label" for="tanggal_pinjam">
                                        Tanggal Mulai <span style="color:#ef4444">*</span>
                                    </label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required
                                           class="pm-input"
                                           value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}">
                                </div>
                                <div>
                                    <label class="pm-label" for="tanggal_kembali">
                                        Tanggal Kembali <span style="color:#ef4444">*</span>
                                    </label>
                                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" required
                                           class="pm-input" value="{{ old('tanggal_kembali') }}">
                                </div>
                            </div>

                            <div style="margin-top:14px;">
                                <label class="pm-label">
                                    Surat Peminjaman <span style="font-weight:400;color:#64748b;">(Opsional)</span>
                                </label>
                                <div class="pm-upload" id="pmUploadZone"
                                     onclick="document.getElementById('surat_peminjaman').click()">
                                    <div id="pmUploadDefault">
                                        <div style="font-size:24px;margin-bottom:4px;">📎</div>
                                        <p style="font-size:13px;font-weight:600;color:#374151;">Klik untuk unggah</p>
                                        <p style="font-size:11px;color:#94a3b8;margin-top:2px;">PDF, JPG, PNG · maks 2MB</p>
                                    </div>
                                    <div id="pmUploadPreview" style="display:none;">
                                        <div style="font-size:24px;margin-bottom:4px;">✅</div>
                                        <p style="font-size:13px;font-weight:600;color:#10b981;" id="pmFileName"></p>
                                    </div>
                                    <input id="surat_peminjaman" name="surat_peminjaman" type="file"
                                           style="display:none;" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>

                            <div style="margin-top:14px;">
                                <label class="pm-label" for="catatan">
                                    Catatan <span style="font-weight:400;color:#64748b;">(Opsional)</span>
                                </label>
                                <textarea name="catatan" id="catatan" rows="3" class="pm-input"
                                          style="resize:vertical;"
                                          placeholder="Contoh: Untuk praktikum Lab Komputer">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="pm-info">
                                <p style="font-size:13px;font-weight:600;color:#1e40af;margin-bottom:5px;">ℹ️ Informasi Penting</p>
                                <ul style="font-size:12px;color:#1d4ed8;list-style:disc;padding-left:16px;line-height:1.9;">
                                    <li>Pengajuan diproses maksimal 1×24 jam</li>
                                    <li>Alat rusak/hilang dikenakan sanksi</li>
                                    <li>Hubungi admin: <strong>admin@example.com</strong></li>
                                </ul>
                            </div>
                        </div>

                        <div style="display:flex;justify-content:flex-end;gap:10px;">
                            <a href="{{ route('dashboard') }}" class="pm-btn-cancel">Batal</a>
                            <button type="submit" id="pmSubmit" class="pm-btn-submit" disabled>
                                ✓ Ajukan Peminjaman
                            </button>
                        </div>
                    </div>

                    {{-- KANAN: KERANJANG --}}
                    <div class="pm-cart-sticky">
                        <div class="pm-card p-5">
                            <div class="pm-cart-hdr">
                                <span style="font-size:15px;font-weight:700;color:#0f172a;">🛒 Keranjang</span>
                                <span class="pm-cart-badge" id="pmCartCount">0</span>
                            </div>
                            <div id="pmCartEmpty" class="pm-cart-empty">
                                <div style="font-size:34px;margin-bottom:6px;">🧺</div>
                                <p style="font-size:13px;font-weight:600;color:#94a3b8;">Belum ada alat dipilih</p>
                                <p style="font-size:12px;color:#cbd5e1;margin-top:3px;">Klik "+ Tambah" pada alat</p>
                            </div>
                            <div id="pmCartList"></div>
                            <div id="pmCartSummary" style="display:none;">
                                <div class="pm-cart-total">
                                    <span style="font-size:13px;color:#64748b;">Total</span>
                                    <span style="font-weight:700;font-size:15px;color:#0f172a;" id="pmTotalUnit">0 unit</span>
                                </div>
                                <button type="button" class="pm-cart-clear" onclick="clearAll()">
                                    🗑️ Kosongkan Keranjang
                                </button>
                            </div>
                        </div>

                        <div class="pm-card p-5" style="margin-top:14px;display:none;" id="pmDurCard">
                            <div class="pm-sec" style="margin-bottom:6px;">Durasi</div>
                            <span class="pm-dur-num" id="pmDurNum">–</span>
                            <p style="font-size:12px;color:#94a3b8;text-align:center;margin-top:2px;">hari peminjaman</p>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @php
    $alatJs = $alat->map(function($a) {
        $catName = is_string($a->kategori) ? $a->kategori : ($a->kategori?->nama ?? '');
        return [
            'id'    => (string)$a->id,
            'nama'  => $a->nama,
            'stok'  => (int)$a->jumlah,
            'cat'   => $catName,
            'img'   => $a->gambar ? asset('storage/'.$a->gambar) : '',
            'avail' => $a->jumlah > 0 && $a->status === 'tersedia',
        ];
    })->values();
    @endphp
    <script>
    // Data alat dari server
    var ALAT_DATA = @json($alatJs);

    // STATE
    var CART = {}; // { id: { ...data, qty: N } }

    // ── TOGGLE: tambah atau hapus dari keranjang ──────────
    function toggleItem(id) {
        if (CART[id]) {
            removeItem(id);
        } else {
            addItem(id);
        }
    }

    function addItem(id) {
        var d = null;
        for (var i = 0; i < ALAT_DATA.length; i++) {
            if (ALAT_DATA[i].id === id) { d = ALAT_DATA[i]; break; }
        }
        if (!d || !d.avail) return;

        CART[id] = { id:d.id, nama:d.nama, stok:d.stok, qty:1, img:d.img };

        // Update tombol di list
        var btn = document.getElementById('btn' + id);
        if (btn) {
            btn.textContent = '✓ Ditambah';
            btn.className   = 'pm-add-btn pm-add-btn-remove';
        }
        // Highlight row
        var row = btn ? btn.closest('.pm-list-item') : null;
        if (row) row.classList.add('added');

        renderCart();
        syncHidden();
    }

    function removeItem(id) {
        delete CART[id];

        var btn = document.getElementById('btn' + id);
        if (btn) {
            btn.textContent = '+ Tambah';
            btn.className   = 'pm-add-btn pm-add-btn-add';
        }
        var row = btn ? btn.closest('.pm-list-item') : null;
        if (row) row.classList.remove('added');

        renderCart();
        syncHidden();
    }

    function changeQty(id, delta) {
        if (!CART[id]) return;
        var next = CART[id].qty + delta;
        if (next < 1) { removeItem(id); return; }
        if (next > CART[id].stok) return;
        CART[id].qty = next;
        renderCart();
        syncHidden();
    }

    function clearAll() {
        var ids = Object.keys(CART);
        for (var i = 0; i < ids.length; i++) removeItem(ids[i]);
    }

    // ── RENDER KERANJANG ─────────────────────────────────
    function renderCart() {
        var ids    = Object.keys(CART);
        var empty  = document.getElementById('pmCartEmpty');
        var list   = document.getElementById('pmCartList');
        var summ   = document.getElementById('pmCartSummary');
        var badge  = document.getElementById('pmCartCount');
        var submit = document.getElementById('pmSubmit');

        badge.textContent = ids.length;

        if (ids.length === 0) {
            empty.style.display = 'block';
            list.innerHTML      = '';
            summ.style.display  = 'none';
            submit.disabled     = true;
            return;
        }

        empty.style.display = 'none';
        summ.style.display  = 'block';
        submit.disabled     = false;

        var html = '';
        var total = 0;
        for (var i = 0; i < ids.length; i++) {
            var id = ids[i];
            var it = CART[id];
            total += it.qty;

            var thumb = it.img
                ? '<img class="pm-ci-img" src="' + it.img + '" alt="" onerror="this.style.display=\'none\'">'
                : '<div class="pm-ci-img-ph">🔧</div>';

            html +=
                '<div class="pm-cart-item">' +
                    thumb +
                    '<div class="pm-ci-info"><div class="pm-ci-name">' + it.nama + '</div></div>' +
                    '<div class="pm-ci-qty-wrap">' +
                        '<button type="button" class="pm-ci-qbtn pm-ci-qbtn-m" onclick="changeQty(\'' + id + '\',-1)">−</button>' +
                        '<span class="pm-ci-qty-num">' + it.qty + '</span>' +
                        '<button type="button" class="pm-ci-qbtn pm-ci-qbtn-p" onclick="changeQty(\'' + id + '\',1)">+</button>' +
                    '</div>' +
                    '<button type="button" class="pm-ci-rm" onclick="removeItem(\'' + id + '\')" title="Hapus">✕</button>' +
                '</div>';
        }
        list.innerHTML = html;
        document.getElementById('pmTotalUnit').textContent = total + ' unit';
    }

    // ── SYNC HIDDEN ──────────────────────────────────────
    function syncHidden() {
        var ids = Object.keys(CART);
        if (ids.length > 0) {
            document.getElementById('h_tool_id').value = ids[0];
            document.getElementById('h_jumlah').value  = CART[ids[0]].qty;
        } else {
            document.getElementById('h_tool_id').value = '';
            document.getElementById('h_jumlah').value  = '';
        }
    }

    // ── SEARCH ───────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('pmSearch').addEventListener('input', filterList);

        // PILLS — event pada span bukan button, aman dari Tailwind form handling
        var pills = document.querySelectorAll('.pm-pill');
        for (var i = 0; i < pills.length; i++) {
            pills[i].addEventListener('click', function() {
                pills = document.querySelectorAll('.pm-pill');
                for (var j = 0; j < pills.length; j++) pills[j].classList.remove('on');
                this.classList.add('on');
                filterList();
            });
        }

        // DATE
        document.getElementById('tanggal_pinjam').addEventListener('change', function() {
            var d = new Date(this.value);
            d.setDate(d.getDate() + 1);
            document.getElementById('tanggal_kembali').min = d.toISOString().split('T')[0];
            calcDur();
        });
        document.getElementById('tanggal_kembali').addEventListener('change', calcDur);
        calcDur();

        // UPLOAD
        document.getElementById('surat_peminjaman').addEventListener('change', function() {
            var f = this.files[0];
            if (f) {
                document.getElementById('pmUploadDefault').style.display = 'none';
                document.getElementById('pmUploadPreview').style.display = 'block';
                document.getElementById('pmFileName').textContent = '📄 ' + f.name;
                document.getElementById('pmUploadZone').classList.add('done');
            }
        });

        renderCart();
    });

    function filterList() {
        var kw  = document.getElementById('pmSearch').value.toLowerCase().trim();
        var ap  = document.querySelector('.pm-pill.on');
        var cat = ap ? ap.getAttribute('data-cat') : 'all';
        var rows = document.querySelectorAll('.pm-list-item');
        var vis  = 0;
        for (var i = 0; i < rows.length; i++) {
            var r  = rows[i];
            var nm = (r.getAttribute('data-nama') || '').toLowerCase();
            var ct = r.getAttribute('data-cat') || '';
            var ok = (!kw || nm.includes(kw)) && (cat === 'all' || ct === cat);
            r.style.display = ok ? '' : 'none';
            if (ok) vis++;
        }
        document.getElementById('pmNoResult').style.display = vis === 0 ? 'block' : 'none';
    }

    function calcDur() {
        var p   = document.getElementById('tanggal_pinjam').value;
        var k   = document.getElementById('tanggal_kembali').value;
        var box = document.getElementById('pmDurCard');
        var num = document.getElementById('pmDurNum');
        if (p && k) {
            var diff = Math.ceil((new Date(k) - new Date(p)) / 86400000);
            if (diff > 0) { num.textContent = diff; box.style.display = 'block'; return; }
        }
        num.textContent = '–';
        box.style.display = 'none';
    }
    </script>

    @push('scripts'){{-- placeholder jika layout membutuhkan --}}@endpush
</x-app-layout>