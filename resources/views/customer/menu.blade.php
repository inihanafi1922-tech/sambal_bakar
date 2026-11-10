<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1c120e">
    <title>Pesan Menu - Sambal Bakar</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f6f1ea;
            --surface: #ffffff;
            --ink: #231a15;
            --muted: #8a7b72;
            --brand: #d62828;
            --brand-dark: #a61b1b;
            --accent: #f77f00;
            --line: #ece2d8;
            --ok: #1e7a3d;
            --shadow: 0 18px 40px -20px rgba(60, 30, 10, .35);
        }

        * { box-sizing: border-box; }

        [hidden] { display: none !important; }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
            padding-bottom: 84px;
        }

        button { font: inherit; cursor: pointer; }

        .topbar {
            background: rgba(28, 18, 14, .97);
            color: #fff;
        }

        .topbar-inner {
            width: min(560px, 100% - 32px);
            margin-inline: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 0;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--accent), var(--brand));
            display: grid;
            place-items: center;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand h1 { font-size: 16px; margin: 0; font-weight: 800; }
        .brand p { margin: 2px 0 0; font-size: 11px; color: #d8c7bd; }

        main {
            width: min(560px, 100% - 28px);
            margin: 18px auto 30px;
        }

        .intro {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px 18px;
            box-shadow: var(--shadow);
            margin-bottom: 16px;
        }

        .intro h2 { margin: 0 0 6px; font-size: 17px; font-weight: 800; }
        .intro p { margin: 0; font-size: 13px; color: var(--muted); line-height: 1.6; }

        .search input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            background: var(--surface);
            color: var(--ink);
            outline: none;
            margin-bottom: 12px;
        }

        .search input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(247, 127, 0, .15); }

        .category-bar {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 6px;
            scrollbar-width: none;
        }

        .category-bar::-webkit-scrollbar { display: none; }

        .chip {
            flex: 0 0 auto;
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--ink);
            padding: 8px 15px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
        }

        .chip.active { background: var(--ink); border-color: var(--ink); color: #fff; }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
        }

        .product-thumb {
            position: relative;
            height: 84px;
            padding: 10px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            background: linear-gradient(135deg, #fde7c8, #f6c39a);
            color: #7a3b12;
            overflow: hidden;
        }

        .product-thumb.has-image { background-size: cover; background-position: center; color: #fff; }
        .product-thumb.has-image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, .45), rgba(0, 0, 0, .1));
        }
        .product-thumb.has-image .thumb-category,
        .product-thumb.has-image .spicy-pill { position: relative; z-index: 1; }

        .thumb-letter {
            position: absolute;
            right: 4px;
            bottom: -20px;
            font-size: 64px;
            font-weight: 800;
            color: rgba(122, 59, 18, .12);
            line-height: 1;
            user-select: none;
        }

        .thumb-category { position: relative; font-size: 10px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; }

        .spicy-pill {
            position: relative;
            background: rgba(214, 40, 40, .14);
            color: var(--brand-dark);
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .product-body { display: flex; flex-direction: column; gap: 10px; flex: 1; padding: 12px; }

        .product-body h3 { margin: 0; font-size: 13px; font-weight: 700; line-height: 1.35; min-height: 36px; }

        .product-foot { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 8px; }

        .price { font-weight: 800; color: var(--brand); font-size: 13px; }

        .add-btn {
            border: 0;
            background: var(--brand);
            color: #fff;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .add-btn:hover { background: var(--brand-dark); }

        .stepper {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 3px;
        }

        .stepper button {
            width: 26px;
            height: 26px;
            border: 0;
            border-radius: 999px;
            background: #f6efe8;
            color: var(--ink);
            font-size: 15px;
            line-height: 1;
            font-weight: 700;
        }

        .stepper span { min-width: 18px; text-align: center; font-size: 12px; font-weight: 800; }

        .empty-menu {
            grid-column: 1 / -1;
            padding: 40px 20px;
            text-align: center;
            color: var(--muted);
            background: var(--surface);
            border: 1px dashed var(--line);
            border-radius: 16px;
            font-size: 14px;
        }

        .cartbar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 40;
            padding: 12px 14px calc(12px + env(safe-area-inset-bottom));
            background: rgba(28, 18, 14, .97);
            backdrop-filter: blur(10px);
        }

        .cartbar-inner {
            width: min(560px, 100%);
            margin-inline: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .cartbar-info { color: #fff; }
        .cartbar-info strong { display: block; font-size: 15px; font-weight: 800; }
        .cartbar-info span { font-size: 11px; color: #d8c7bd; }

        .cartbar-btn {
            border: 0;
            background: var(--accent);
            color: #231a15;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
        }

        .sheet {
            position: fixed;
            inset: 0;
            z-index: 60;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .sheet-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(28, 18, 14, .55);
            backdrop-filter: blur(2px);
        }

        .sheet-card {
            position: relative;
            width: min(560px, 100%);
            max-height: 88vh;
            overflow: auto;
            background: var(--surface);
            border-radius: 22px 22px 0 0;
            box-shadow: 0 -20px 50px -20px rgba(0, 0, 0, .45);
        }

        .sheet-head {
            position: sticky;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            background: var(--surface);
            z-index: 1;
        }

        .sheet-head h2 { margin: 0; font-size: 16px; font-weight: 800; }

        .sheet-close {
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 999px;
            background: #f6efe8;
            color: var(--ink);
            font-size: 20px;
            line-height: 1;
        }

        .sheet-body { padding: 16px 18px; display: flex; flex-direction: column; gap: 14px; }

        .field label { display: block; font-size: 12px; font-weight: 700; color: var(--muted); margin-bottom: 7px; }

        .field input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 15px;
            font-weight: 700;
            background: #fdfaf6;
            color: var(--ink);
            outline: none;
        }

        .field input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(247, 127, 0, .15); }

        .order-item { border: 1px solid var(--line); border-radius: 14px; padding: 11px 12px; }

        .oi-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; font-size: 13px; }
        .oi-top strong { font-weight: 700; line-height: 1.35; }
        .oi-top span { white-space: nowrap; font-weight: 700; color: var(--brand); font-size: 12px; }

        .oi-bottom { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 10px; }

        .level-picker { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; color: var(--muted); }

        .level-picker select {
            border: 1px solid var(--line);
            border-radius: 9px;
            padding: 5px 7px;
            background: #fff;
            color: var(--ink);
            font-size: 11px;
            font-weight: 600;
        }

        .order-empty { margin: 0; padding: 18px 4px; text-align: center; color: var(--muted); font-size: 13px; }

        .payment-options { display: flex; flex-direction: column; gap: 10px; }

        .payment-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            cursor: pointer;
        }

        .payment-option input { margin-top: 2px; }
        .payment-option strong { display: block; font-size: 13px; }
        .payment-option span { display: block; font-size: 12px; color: var(--muted); margin-top: 2px; }

        .payment-option:has(input:checked) {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(247, 127, 0, .12);
        }

        .pay-info {
            border: 1px dashed #d8c8bb;
            border-radius: 14px;
            padding: 14px;
            background: #fdfaf6;
            text-align: center;
        }

        .pay-info p { margin: 10px 0 0; font-size: 12px; color: var(--muted); line-height: 1.5; }

        .qris-box {
            display: inline-flex;
            padding: 10px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
        }

        .qris-box img { width: 200px; height: 200px; object-fit: contain; border-radius: 8px; display: block; }

        .rekening-box { text-align: left; display: flex; flex-direction: column; gap: 8px; }

        .rekening-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 13px;
            border-bottom: 1px dashed #e6d6c5;
            padding-bottom: 8px;
        }
        .rekening-row:last-child { border-bottom: 0; padding-bottom: 0; }
        .rekening-row span { color: var(--muted); }
        .rekening-row strong { text-align: right; }

        .sheet-foot {
            position: sticky;
            bottom: 0;
            padding: 16px 18px calc(16px + env(safe-area-inset-bottom));
            border-top: 1px solid var(--line);
            background: #fdfaf6;
        }

        .total-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .total-row strong { font-size: 21px; color: var(--brand); }

        .submit-btn {
            width: 100%;
            border: 0;
            border-radius: 13px;
            background: var(--brand);
            color: #fff;
            padding: 14px;
            font-weight: 800;
            font-size: 15px;
        }

        .submit-btn:hover:not(:disabled) { background: var(--brand-dark); }
        .submit-btn:disabled { opacity: .45; cursor: not-allowed; }

        .success {
            position: fixed;
            inset: 0;
            z-index: 70;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(28, 18, 14, .6);
            backdrop-filter: blur(3px);
        }

        .success-card {
            width: min(420px, 100%);
            background: var(--surface);
            border-radius: 22px;
            padding: 28px 24px;
            text-align: center;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, .45);
        }

        .success-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 14px;
            border-radius: 999px;
            background: #e6f4ea;
            color: var(--ok);
            display: grid;
            place-items: center;
            font-size: 30px;
            font-weight: 800;
        }

        .success-card h2 { margin: 0 0 6px; font-size: 20px; font-weight: 800; }
        .success-order { margin: 0 0 6px; font-size: 14px; color: var(--muted); }
        .success-order strong { color: var(--ink); }
        .success-total { margin: 0 0 14px; font-size: 16px; font-weight: 800; color: var(--brand); }

        .success-barcode { display: flex; justify-content: center; margin-bottom: 10px; }
        .success-barcode svg { max-width: 100%; }
        .success-code { margin: 0 0 8px; font-size: 14px; color: var(--muted); }
        .success-code strong { color: var(--ink); letter-spacing: 1.5px; }
        .track-link {
            display: inline-block;
            margin-bottom: 14px;
            font-size: 13px;
            font-weight: 700;
            color: var(--brand);
            text-decoration: none;
        }
        .track-link:hover { text-decoration: underline; }

        .intro p a { color: var(--brand); font-weight: 700; text-decoration: none; }
        .intro p a:hover { text-decoration: underline; }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 999px;
            background: #fff3e0;
            color: #9a5b00;
            font-size: 13px;
            font-weight: 700;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: var(--accent);
            animation: pulse 1.4s ease infinite;
        }

        @keyframes pulse { 50% { opacity: .3; } }

        .status-pill.done { background: #e6f4ea; color: var(--ok); }
        .status-pill.done .status-dot { background: var(--ok); animation: none; }

        .success-note { margin: 16px 0 20px; font-size: 13px; color: var(--muted); line-height: 1.6; }
        .success-note strong { color: var(--ink); }

        .again-btn {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 13px;
            background: #fff;
            color: var(--ink);
            padding: 13px;
            font-weight: 700;
            font-size: 14px;
        }

        .toast {
            position: fixed;
            left: 50%;
            bottom: 100px;
            z-index: 80;
            width: max-content;
            max-width: 90vw;
            padding: 12px 18px;
            border-radius: 12px;
            background: #1f140f;
            color: #fff;
            font-size: 14px;
            text-align: center;
            opacity: 0;
            pointer-events: none;
            transform: translate(-50%, 16px);
            transition: opacity .25s ease, transform .25s ease;
        }

        .toast.show { opacity: 1; transform: translate(-50%, 0); }
        .toast.error { background: var(--brand-dark); }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <span class="brand-mark">SB</span>
            <div class="brand">
                <h1>Sambal Bakar</h1>
                <p>Pesan langsung dari HP kamu</p>
            </div>
        </div>
    </header>

    <main>
        <section class="intro">
            <h2>Pilih menu favoritmu</h2>
            <p>Tambahkan menu ke keranjang, isi nama atau nomor meja, lalu kirim pesanan. Bayar dan ambil pesananmu di kasir.</p>
            <p>Sudah pesan? <a href="{{ url('/lacak') }}">Lacak pesananmu di sini</a>.</p>
        </section>

        <div class="search">
            <input type="search" id="searchInput" placeholder="Cari menu..." autocomplete="off">
        </div>

        <div class="category-bar" id="categoryBar">
            <button type="button" class="chip active" data-cat="all">Semua</button>
            @foreach ($products->pluck('category')->unique() as $category)
                <button type="button" class="chip" data-cat="{{ $category }}">{{ $category }}</button>
            @endforeach
        </div>

        <div class="product-grid" id="productGrid">
            @forelse ($products as $product)
                <article
                    class="product-card"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->price }}"
                    data-spicy="{{ $product->has_spicy_level ? 1 : 0 }}"
                    data-category="{{ $product->category }}"
                >
                    <div class="product-thumb {{ $product->image ? 'has-image' : '' }}" @if ($product->image) style="background-image:url('{{ '/'.$product->image }}')" @endif>
                        <span class="thumb-category">{{ $product->category }}</span>
                        @if ($product->has_spicy_level)
                            <span class="spicy-pill">Pedas</span>
                        @endif
                        @unless ($product->image)
                            <span class="thumb-letter">{{ strtoupper(substr($product->category, 0, 1)) }}</span>
                        @endunless
                    </div>
                    <div class="product-body">
                        <h3>{{ $product->name }}</h3>
                        <div class="product-foot">
                            <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <button type="button" class="add-btn" data-add>Tambah</button>
                            <div class="stepper" hidden data-stepper>
                                <button type="button" data-action="dec" aria-label="Kurangi">&minus;</button>
                                <span data-card-qty>0</span>
                                <button type="button" data-action="inc" aria-label="Tambah">+</button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-menu">Belum ada menu tersedia saat ini.</div>
            @endforelse
        </div>
    </main>

    <div class="cartbar" id="cartBar" hidden>
        <div class="cartbar-inner">
            <div class="cartbar-info">
                <strong id="cartBarTotal">Rp 0</strong>
                <span id="cartBarCount">0 item</span>
            </div>
            <button type="button" class="cartbar-btn" id="openCartBtn">Lihat Pesanan</button>
        </div>
    </div>

    <div class="sheet" id="cartSheet" hidden>
        <div class="sheet-backdrop" data-close-cart></div>
        <div class="sheet-card" role="dialog" aria-modal="true" aria-labelledby="cartSheetTitle">
            <div class="sheet-head">
                <h2 id="cartSheetTitle">Pesanan Kamu</h2>
                <button type="button" class="sheet-close" data-close-cart aria-label="Tutup">&times;</button>
            </div>
            <div class="sheet-body">
                <div class="field">
                    <label for="customerName">Nama / Nomor Meja</label>
                    <input type="text" id="customerName" placeholder="Contoh: Budi atau Meja 5" autocomplete="off" value="{{ $tableHint !== '' ? 'Meja '.$tableHint : '' }}">
                </div>

                <div id="orderItems">
                    <p class="order-empty">Keranjang masih kosong.</p>
                </div>

                <div class="payment-options" id="paymentOptions">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cash" checked>
                        <div>
                            <strong>Tunai</strong>
                            <span>Bayar tunai di kasir saat mengambil pesanan.</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="transfer">
                        <div>
                            <strong>Transfer</strong>
                            <span>Transfer ke rekening toko, lalu tunjukkan bukti ke kasir.</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="qris">
                        <div>
                            <strong>QRIS</strong>
                            <span>Scan QRIS dari aplikasi bank atau e-wallet kamu.</span>
                        </div>
                    </label>
                </div>

                @php($setting = $paymentSetting)
                <div class="pay-info" id="qrisInfo" hidden>
                    @if ($setting?->hasQris())
                        <div class="qris-box">
                            <img src="{{ '/'.$setting->qris_image }}" alt="QRIS Sambal Bakar">
                        </div>
                        <p>Scan QRIS di atas dari aplikasi bank atau e-wallet kamu.</p>
                    @else
                        <p>QRIS belum tersedia. Silakan bayar di kasir saat mengambil pesanan.</p>
                    @endif
                </div>
                <div class="pay-info" id="transferInfo" hidden>
                    @if ($setting?->hasRekening())
                        <div class="rekening-box">
                            <div class="rekening-row">
                                <span>Bank / E-Wallet</span>
                                <strong>{{ $setting->bank_name }}</strong>
                            </div>
                            <div class="rekening-row">
                                <span>Nomor</span>
                                <strong>{{ $setting->account_number }}</strong>
                            </div>
                            @if ($setting->account_holder)
                                <div class="rekening-row">
                                    <span>Atas Nama</span>
                                    <strong>{{ $setting->account_holder }}</strong>
                                </div>
                            @endif
                        </div>
                        <p>Transfer sesuai total pesanan, lalu tunjukkan bukti transfer ke kasir.</p>
                    @else
                        <p>Info rekening belum tersedia. Silakan bayar di kasir saat mengambil pesanan.</p>
                    @endif
                </div>
            </div>
            <div class="sheet-foot">
                <div class="total-row">
                    <span>Total</span>
                    <strong id="orderTotal">Rp 0</strong>
                </div>
                <button type="button" class="submit-btn" id="submitOrder" disabled>Kirim Pesanan</button>
            </div>
        </div>
    </div>

    <div class="success" id="successView" hidden>
        <div class="success-card">
            <div class="success-icon">&#10003;</div>
            <h2>Pesanan Terkirim!</h2>
            <p class="success-order">Nomor order kamu: <strong id="successOrderId">#-</strong></p>
            <p class="success-total" id="successTotal"></p>
            <div class="success-barcode"><svg id="successBarcode"></svg></div>
            <p class="success-code">Kode lacak: <strong id="successTrackCode">-</strong></p>
            <a class="track-link" id="trackLink" href="{{ url('/lacak') }}" target="_blank" rel="noopener">Buka halaman lacak pesanan &rarr;</a>
            <span class="status-pill" id="successStatusPill"><span class="status-dot"></span><span id="successStatusText">Menunggu Konfirmasi</span></span>
            <div class="pay-info" id="successPayQris" hidden style="margin-top:16px;">
                @if ($paymentSetting?->hasQris())
                    <div class="qris-box">
                        <img src="{{ '/'.$paymentSetting->qris_image }}" alt="QRIS Sambal Bakar">
                    </div>
                    <p>Scan QRIS di atas untuk membayar pesanan kamu.</p>
                @else
                    <p>QRIS belum tersedia. Silakan bayar di kasir saat mengambil pesanan.</p>
                @endif
            </div>
            <div class="pay-info" id="successPayTransfer" hidden style="margin-top:16px;">
                @if ($paymentSetting?->hasRekening())
                    <div class="rekening-box">
                        <div class="rekening-row">
                            <span>Bank / E-Wallet</span>
                            <strong>{{ $paymentSetting->bank_name }}</strong>
                        </div>
                        <div class="rekening-row">
                            <span>Nomor</span>
                            <strong>{{ $paymentSetting->account_number }}</strong>
                        </div>
                        @if ($paymentSetting->account_holder)
                            <div class="rekening-row">
                                <span>Atas Nama</span>
                                <strong>{{ $paymentSetting->account_holder }}</strong>
                            </div>
                        @endif
                    </div>
                    <p>Transfer sesuai total pesanan, lalu tunjukkan bukti transfer ke kasir.</p>
                @else
                    <p>Info rekening belum tersedia. Silakan bayar di kasir saat mengambil pesanan.</p>
                @endif
            </div>
            <p class="success-note" id="successNote">Pesanan kamu menunggu konfirmasi kasir. Silakan lanjutkan <strong>pembayaran di kasir</strong> dan tunjukkan nomor order ini.</p>
            <button type="button" class="again-btn" id="orderAgainBtn">Pesan Lagi</button>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        const grid = document.getElementById('productGrid');
        const categoryBar = document.getElementById('categoryBar');
        const searchInput = document.getElementById('searchInput');
        const cartBar = document.getElementById('cartBar');
        const cartBarTotal = document.getElementById('cartBarTotal');
        const cartBarCount = document.getElementById('cartBarCount');
        const openCartBtn = document.getElementById('openCartBtn');
        const cartSheet = document.getElementById('cartSheet');
        const orderItemsEl = document.getElementById('orderItems');
        const orderTotalEl = document.getElementById('orderTotal');
        const customerNameInput = document.getElementById('customerName');
        const submitBtn = document.getElementById('submitOrder');
        const successView = document.getElementById('successView');
        const successOrderId = document.getElementById('successOrderId');
        const successTotal = document.getElementById('successTotal');
        const successStatusPill = document.getElementById('successStatusPill');
        const successStatusText = document.getElementById('successStatusText');
        const successNote = document.getElementById('successNote');
        const successPayQris = document.getElementById('successPayQris');
        const successPayTransfer = document.getElementById('successPayTransfer');
        const successTrackCode = document.getElementById('successTrackCode');
        const trackLink = document.getElementById('trackLink');
        const orderAgainBtn = document.getElementById('orderAgainBtn');
        const toastEl = document.getElementById('toast');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const orderEndpoint = '{{ route('pelanggan.order.store') }}';

        const cart = new Map();
        let activeCategory = 'all';
        let searchTerm = '';
        let statusUrl = null;
        let pollTimer = null;

        const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (char) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[char]));

        function showToast(message, isError = false) {
            toastEl.textContent = message;
            toastEl.classList.toggle('error', isError);
            toastEl.classList.add('show');
            clearTimeout(showToast.timer);
            showToast.timer = setTimeout(() => toastEl.classList.remove('show'), 3000);
        }

        function applyFilter() {
            document.querySelectorAll('.product-card').forEach((card) => {
                const matchCategory = activeCategory === 'all' || card.dataset.category === activeCategory;
                const matchSearch = searchTerm === '' || card.dataset.name.toLowerCase().includes(searchTerm);
                card.hidden = !(matchCategory && matchSearch);
            });
        }

        function cartTotal() {
            let total = 0;
            for (const item of cart.values()) total += item.price * item.qty;
            return total;
        }

        function cartCount() {
            let count = 0;
            for (const item of cart.values()) count += item.qty;
            return count;
        }

        function render() {
            let html = '';

            for (const item of cart.values()) {
                html += `
                    <div class="order-item">
                        <div class="oi-top">
                            <strong>${escapeHtml(item.name)}</strong>
                            <span>${rupiah(item.price * item.qty)}</span>
                        </div>
                        <div class="oi-bottom">
                            <div class="stepper">
                                <button type="button" data-action="dec" data-id="${item.id}" aria-label="Kurangi">&minus;</button>
                                <span>${item.qty}</span>
                                <button type="button" data-action="inc" data-id="${item.id}" aria-label="Tambah">+</button>
                            </div>
                            ${item.spicy ? `
                                <label class="level-picker">Level
                                    <select data-level data-id="${item.id}">
                                        ${[1, 2, 3, 4, 5].map((level) => `
                                            <option value="${level}" ${item.level === level ? 'selected' : ''}>${level}</option>
                                        `).join('')}
                                    </select>
                                </label>
                            ` : ''}
                        </div>
                    </div>
                `;
            }

            orderItemsEl.innerHTML = html || '<p class="order-empty">Keranjang masih kosong.</p>';
            orderTotalEl.textContent = rupiah(cartTotal());

            const count = cartCount();
            cartBar.hidden = count === 0;
            cartBarTotal.textContent = rupiah(cartTotal());
            cartBarCount.textContent = count + ' item';
            submitBtn.disabled = count === 0;

            document.querySelectorAll('.product-card').forEach((card) => {
                const item = cart.get(card.dataset.id);
                const stepper = card.querySelector('[data-stepper]');
                const addBtn = card.querySelector('[data-add]');

                if (item) {
                    card.querySelector('[data-card-qty]').textContent = item.qty;
                    addBtn.hidden = true;
                    stepper.hidden = false;
                } else {
                    addBtn.hidden = false;
                    stepper.hidden = true;
                }
            });
        }

        function addToCart(card) {
            const id = card.dataset.id;
            const existing = cart.get(id);

            if (existing) {
                existing.qty += 1;
            } else {
                cart.set(id, {
                    id: id,
                    name: card.dataset.name,
                    price: Number(card.dataset.price),
                    spicy: card.dataset.spicy === '1',
                    level: 1,
                    qty: 1
                });
            }

            render();
        }

        function changeQty(id, delta) {
            const item = cart.get(id);
            if (!item) return;

            item.qty += delta;
            if (item.qty <= 0) cart.delete(id);

            render();
        }

        grid.addEventListener('click', (event) => {
            const addButton = event.target.closest('[data-add]');
            if (addButton) {
                addToCart(addButton.closest('.product-card'));
                return;
            }

            const stepButton = event.target.closest('[data-action]');
            if (stepButton) changeQty(stepButton.dataset.id, stepButton.dataset.action === 'inc' ? 1 : -1);
        });

        orderItemsEl.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-action]');
            if (button) changeQty(button.dataset.id, button.dataset.action === 'inc' ? 1 : -1);
        });

        orderItemsEl.addEventListener('change', (event) => {
            const select = event.target.closest('select[data-level]');
            if (!select) return;

            const item = cart.get(select.dataset.id);
            if (item) item.level = Number(select.value);
        });

        categoryBar.addEventListener('click', (event) => {
            const chip = event.target.closest('[data-cat]');
            if (!chip) return;

            categoryBar.querySelectorAll('.chip').forEach((item) => item.classList.remove('active'));
            chip.classList.add('active');
            activeCategory = chip.dataset.cat;
            applyFilter();
        });

        searchInput.addEventListener('input', () => {
            searchTerm = searchInput.value.trim().toLowerCase();
            applyFilter();
        });

        function openCart() {
            cartSheet.hidden = false;
            document.body.style.overflow = 'hidden';
        }

        function closeCart() {
            cartSheet.hidden = true;
            document.body.style.overflow = '';
        }

        openCartBtn.addEventListener('click', openCart);

        cartSheet.querySelectorAll('[data-close-cart]').forEach((element) => {
            element.addEventListener('click', closeCart);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !cartSheet.hidden) closeCart();
        });

        function selectedPaymentMethod() {
            const checked = cartSheet.querySelector('input[name="payment_method"]:checked');
            return checked ? checked.value : 'cash';
        }

        function togglePaymentInfo() {
            const method = selectedPaymentMethod();
            const qrisInfo = document.getElementById('qrisInfo');
            const transferInfo = document.getElementById('transferInfo');

            if (qrisInfo) qrisInfo.hidden = method !== 'qris';
            if (transferInfo) transferInfo.hidden = method !== 'transfer';
        }

        cartSheet.querySelectorAll('input[name="payment_method"]').forEach((input) => {
            input.addEventListener('change', togglePaymentInfo);
        });

        function stopPolling() {
            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        }

        function renderBarcode(code) {
            try {
                if (window.JsBarcode) {
                    JsBarcode('#successBarcode', code, { width: 2, height: 64, displayValue: false, margin: 0 });
                }
            } catch (error) {
                return;
            }
        }

        function saveActiveOrder(orderId, url, method, code) {
            try {
                localStorage.setItem('sbActiveOrder', JSON.stringify({ id: orderId, url: url, method: method, code: code }));
            } catch (error) {
                return;
            }
        }

        function clearActiveOrder() {
            try {
                localStorage.removeItem('sbActiveOrder');
            } catch (error) {
                return;
            }
        }

        function showSuccess(orderId, paymentMethod, trackCode) {
            successOrderId.textContent = '#' + orderId;
            successStatusText.textContent = 'Menunggu Konfirmasi';
            successStatusPill.classList.remove('done');
            successNote.innerHTML = 'Pesanan kamu menunggu konfirmasi kasir. Tunjukkan nomor order ini ke kasir.';
            successPayQris.hidden = paymentMethod !== 'qris';
            successPayTransfer.hidden = paymentMethod !== 'transfer';
            successTrackCode.textContent = trackCode || '-';
            trackLink.href = trackCode
                ? '{{ url('/lacak') }}/' + encodeURIComponent(trackCode)
                : '{{ url('/lacak') }}';
            if (trackCode) renderBarcode(trackCode);
            successView.hidden = false;
        }

        async function refreshStatus() {
            if (!statusUrl) return;

            try {
                const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) return;

                const data = await response.json();
                successStatusText.textContent = data.status_label;
                successStatusPill.classList.toggle('done', data.status === 'served');

                if (data.status === 'served') {
                    successNote.innerHTML = 'Pesanan kamu sudah <strong>selesai disajikan</strong>. Selamat menikmati!';
                    clearActiveOrder();
                    stopPolling();
                } else if (data.paid) {
                    successNote.innerHTML = 'Pembayaran sudah <strong>dikonfirmasi kasir</strong>. Pesanan sedang diproses dapur.';
                }
            } catch (error) {
                return;
            }
        }

        function restoreActiveOrder() {
            let saved = null;

            try {
                saved = JSON.parse(localStorage.getItem('sbActiveOrder') || 'null');
            } catch (error) {
                clearActiveOrder();
                return;
            }

            if (!saved || !saved.id || !saved.url) return;

            statusUrl = saved.url;
            successTotal.textContent = '';
            showSuccess(saved.id, saved.method || 'cash', saved.code || null);

            refreshStatus();
            pollTimer = setInterval(refreshStatus, 5000);
        }

        submitBtn.addEventListener('click', async () => {
            if (cart.size === 0) return;

            const customerName = customerNameInput.value.trim();
            if (customerName === '') {
                showToast('Isi nama atau nomor meja dulu ya', true);
                customerNameInput.focus();
                return;
            }

            const items = Array.from(cart.values()).map((item) => ({
                product_id: Number(item.id),
                qty: item.qty,
                spicy_level: item.spicy ? item.level : null
            }));
            const paymentMethod = selectedPaymentMethod();

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';

            try {
                const response = await fetch(orderEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        table_number: customerName,
                        payment_method: paymentMethod,
                        items: items
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    const message = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal mengirim pesanan.');
                    throw new Error(message);
                }

                cart.clear();
                render();
                closeCart();

                statusUrl = data.status_url;
                showSuccess(data.order_id, paymentMethod, data.tracking_code);
                successTotal.textContent = 'Total: ' + rupiah(data.total_amount);
                saveActiveOrder(data.order_id, data.status_url, paymentMethod, data.tracking_code);

                stopPolling();
                refreshStatus();
                pollTimer = setInterval(refreshStatus, 5000);
            } catch (error) {
                showToast(error.message, true);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim Pesanan';
            }
        });

        orderAgainBtn.addEventListener('click', () => {
            stopPolling();
            clearActiveOrder();
            statusUrl = null;
            successView.hidden = true;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        render();
        restoreActiveOrder();
    </script>
</body>
</html>
