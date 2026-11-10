<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1c120e">
    <title>Kasir - Sambal Bakar</title>
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
            --shadow: 0 18px 40px -20px rgba(60, 30, 10, .35);
        }

        * { box-sizing: border-box; }

        [hidden] { display: none !important; }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        button { font: inherit; cursor: pointer; }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            background: rgba(28, 18, 14, .97);
            color: #fff;
            backdrop-filter: blur(10px);
        }

        .topbar-inner {
            width: min(1280px, 100% - 32px);
            margin-inline: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 13px 0;
        }

        .brand { display: flex; align-items: center; gap: 12px; }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--accent), var(--brand));
            display: grid;
            place-items: center;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand h1 { font-size: 17px; margin: 0; font-weight: 800; }
        .brand p { margin: 0; font-size: 12px; color: #d8c7bd; }

        .topnav { display: flex; align-items: center; gap: 8px; }

        .topnav a,
        .topnav span {
            display: inline-flex;
            align-items: center;
            padding: 9px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: #e7d8cf;
            border: 1px solid transparent;
        }

        .topnav a:hover { background: rgba(255, 255, 255, .1); }

        .topnav .active {
            background: var(--accent);
            color: #231a15;
            border-color: var(--accent);
        }

        .topnav .logout-btn {
            border: 1px solid rgba(255, 255, 255, .2);
            background: transparent;
            color: #fff;
            border-radius: 999px;
            padding: 8px 13px;
            font-size: 13px;
            font-weight: 600;
        }

        .topnav .logout-btn:hover { background: rgba(255, 255, 255, .12); }

        .notif-badge {
            display: inline-grid;
            place-items: center;
            min-width: 18px;
            height: 18px;
            margin-left: 6px;
            padding: 0 5px;
            border-radius: 999px;
            background: var(--brand);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            line-height: 1;
        }

        .workspace {
            width: min(1280px, 100% - 32px);
            margin: 22px auto 40px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 390px;
            gap: 20px;
            align-items: start;
        }

        .catalog {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .catalog-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .catalog-head h2 { margin: 0; font-size: 20px; font-weight: 800; }
        .catalog-head p { margin: 4px 0 0; font-size: 13px; color: var(--muted); }

        .search {
            position: relative;
            flex: 0 1 280px;
            min-width: 200px;
        }

        .search input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 14px;
            background: #fdfaf6;
            color: var(--ink);
            outline: none;
        }

        .search input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(247, 127, 0, .15); }

        .category-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin: 18px 0;
        }

        .chip {
            border: 1px solid var(--line);
            background: #fdfaf6;
            color: var(--ink);
            padding: 8px 15px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            transition: .18s ease;
        }

        .chip:hover { border-color: #d8c8bb; }

        .chip.active { background: var(--ink); border-color: var(--ink); color: #fff; }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 30px -24px rgba(60, 30, 10, .45);
        }

        .product-thumb {
            position: relative;
            height: 74px;
            padding: 11px;
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
            right: 6px;
            bottom: -22px;
            font-size: 72px;
            font-weight: 800;
            color: rgba(122, 59, 18, .12);
            line-height: 1;
            user-select: none;
        }

        .thumb-category {
            position: relative;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .spicy-pill {
            position: relative;
            background: rgba(214, 40, 40, .14);
            color: var(--brand-dark);
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .product-body {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
            padding: 13px;
        }

        .product-body h3 { margin: 0; font-size: 14px; font-weight: 700; line-height: 1.35; min-height: 38px; }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: auto;
        }

        .price { font-weight: 800; color: var(--brand); font-size: 14px; }

        .add-wrap { display: inline-flex; align-items: center; gap: 6px; }

        .card-qty {
            display: inline-grid;
            place-items: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            background: #fde8d6;
            color: var(--brand-dark);
            font-size: 11px;
            font-weight: 800;
        }

        .add-btn {
            border: 0;
            background: var(--brand);
            color: #fff;
            padding: 8px 12px;
            border-radius: 11px;
            font-size: 12px;
            font-weight: 700;
            transition: background .18s ease;
        }

        .add-btn:hover { background: var(--brand-dark); }

        .empty-menu {
            grid-column: 1 / -1;
            padding: 40px 20px;
            text-align: center;
            color: var(--muted);
            background: #fdfaf6;
            border: 1px dashed var(--line);
            border-radius: 16px;
            font-size: 14px;
        }

        .order-panel {
            position: sticky;
            top: 86px;
            display: flex;
            flex-direction: column;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 22px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
        }

        .panel-head h2 { margin: 0; font-size: 16px; font-weight: 800; }

        .panel-tag {
            background: #fde8d6;
            color: var(--brand-dark);
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
        }

        .table-field { padding: 16px 20px; border-bottom: 1px solid var(--line); }

        .table-field label { display: block; font-size: 12px; font-weight: 700; color: var(--muted); margin-bottom: 7px; }

        .table-field input,
        .table-field select {
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

        .table-field input:focus,
        .table-field select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(247, 127, 0, .15); }

        .order-items {
            flex: 1;
            overflow: auto;
            max-height: 46vh;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 14px 20px;
        }

        .order-empty { margin: 0; padding: 24px 4px; text-align: center; color: var(--muted); font-size: 13px; }

        .order-item { border: 1px solid var(--line); border-radius: 14px; padding: 11px 12px; }

        .oi-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; font-size: 13px; }
        .oi-top strong { font-weight: 700; line-height: 1.35; }
        .oi-top span { white-space: nowrap; font-weight: 700; color: var(--brand); font-size: 12px; }

        .oi-bottom { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 10px; }

        .qty-control {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 3px;
        }

        .qty-control button {
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

        .qty-control span { min-width: 20px; text-align: center; font-size: 12px; font-weight: 700; }

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

        .panel-foot { padding: 16px 20px; border-top: 1px solid var(--line); background: #fdfaf6; }

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
            transition: background .18s ease, opacity .18s ease;
        }

        .submit-btn:hover:not(:disabled) { background: var(--brand-dark); }
        .submit-btn:disabled { opacity: .45; cursor: not-allowed; }

        .reset-btn {
            width: 100%;
            margin-top: 9px;
            border: 1px solid var(--line);
            border-radius: 13px;
            background: #fff;
            color: var(--ink);
            padding: 11px;
            font-weight: 700;
            font-size: 13px;
        }

        .reset-btn:hover { border-color: #d8c8bb; }

        .panel-note { margin: 10px 0 0; text-align: center; font-size: 12px; color: var(--muted); min-height: 15px; }

        .flash {
            grid-column: 1 / -1;
            padding: 13px 16px;
            border-radius: 14px;
            background: #e6f4ea;
            border: 1px solid #bfe3c9;
            color: #1e7a3d;
            font-size: 13px;
            font-weight: 700;
        }

        .payments { grid-column: 1 / -1; overflow: hidden; }
        .payments .panel-head { border-bottom: 1px solid var(--line); }
        .pay-list { display: flex; flex-direction: column; }
        .pay-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 13px 20px;
            border-bottom: 1px solid #f4ece4;
            flex-wrap: wrap;
        }
        .pay-row:last-child { border-bottom: 0; }
        .pay-meta { font-size: 12px; color: var(--muted); margin-top: 3px; }
        .pay-right { display: flex; align-items: center; gap: 12px; }
        .pay-amount { font-weight: 800; color: var(--brand); }
        .pay-status {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }
        .pay-status.paid { background: #e6f4ea; color: #1e7a3d; }
        .pay-status.pending { background: #fff3e0; color: #9a5b00; }
        .pay-link { font-size: 12px; font-weight: 700; color: var(--brand); text-decoration: none; }
        .pay-link:hover { text-decoration: underline; }
        .pay-empty { padding: 22px 20px; text-align: center; color: var(--muted); font-size: 13px; }
        .inline-form { display: inline; margin: 0; }

        .toast {
            position: fixed;
            left: 50%;
            bottom: 28px;
            z-index: 60;
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

        .pay-modal {
            position: fixed;
            inset: 0;
            z-index: 80;
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .pay-modal[hidden] { display: none !important; }

        .pay-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(28, 18, 14, .55);
            backdrop-filter: blur(2px);
        }

        .pay-modal-card {
            position: relative;
            width: min(440px, 100%);
            max-height: 90vh;
            overflow: auto;
            background: var(--surface);
            border-radius: 20px;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, .45);
        }

        .pay-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
        }

        .pay-modal-head h3 { margin: 0; font-size: 17px; font-weight: 800; }

        .pay-modal-close {
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 999px;
            background: #f6efe8;
            color: var(--ink);
            font-size: 20px;
            line-height: 1;
        }

        .pay-modal-body { padding: 18px 20px; }

        .pay-summary {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .pay-summary-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 0;
        }

        .pay-summary-row span:last-child { font-weight: 700; text-align: right; }

        .pay-summary-total {
            margin-top: 8px;
            padding-top: 10px;
            border-top: 1px dashed var(--line);
            font-size: 15px;
            align-items: center;
        }

        .pay-summary-total strong { color: var(--brand); font-size: 19px; }

        .payment-options { display: flex; flex-direction: column; gap: 10px; }

        .payment-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .payment-option input { margin-top: 2px; }
        .payment-option strong { display: block; font-size: 13px; }
        .payment-option span { display: block; font-size: 12px; color: var(--muted); margin-top: 2px; }

        .payment-option:has(input:checked) {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(247, 127, 0, .12);
        }

        .pay-modal-foot {
            display: flex;
            gap: 10px;
            padding: 16px 20px;
            border-top: 1px solid var(--line);
            background: #fdfaf6;
        }

        .pay-modal-foot .submit-btn,
        .pay-modal-foot .reset-btn { flex: 1; margin: 0; }

        @media (max-width: 920px) {
            .workspace { grid-template-columns: 1fr; }
            .order-panel { position: static; }
            .order-items { max-height: none; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <span class="brand-mark">SB</span>
                <div>
                    <h1>Sambal Bakar</h1>
                    <p>Dashboard Kasir</p>
                </div>
            </div>
            <nav class="topnav">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Staf</a>
                @else
                    <a href="{{ route('kasir.staff') }}">Staf</a>
                @endif
                <span class="active">Kasir</span>
                <a href="{{ route('kasir.payments') }}">Pembayaran <span class="notif-badge" id="paymentBadge" hidden></span></a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="workspace">
        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        <section class="catalog">
            <div class="catalog-head">
                <div>
                    <h2>Input Pesanan</h2>
                    <p>Pilih menu, masukkan ke keranjang, lalu lanjut ke pembayaran.</p>
                </div>
                <div class="search">
                    <input type="search" id="searchInput" placeholder="Cari menu..." autocomplete="off">
                </div>
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
                            <div class="product-footer">
                                <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <div class="add-wrap">
                                    <span class="card-qty" data-card-qty hidden>0</span>
                                    <button type="button" class="add-btn" data-add>Tambah</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-menu">Belum ada menu tersedia. Tambahkan produk terlebih dahulu.</div>
                @endforelse
            </div>
        </section>

        <aside class="order-panel">
            <div class="panel-head">
                <h2>Keranjang Pesanan</h2>
                <span class="panel-tag" id="cartCountTag">0 item</span>
            </div>

            <div class="table-field">
                <label for="tableNumber">Nomor Meja / Nama Pelanggan</label>
                <input type="text" id="tableNumber" placeholder="Contoh: 5 atau Budi" autocomplete="off">
            </div>

            <div class="order-items" id="orderItems">
                <p class="order-empty">Belum ada item. Pilih menu di sebelah kiri.</p>
            </div>

            <div class="panel-foot">
                <div class="total-row">
                    <span>Total</span>
                    <strong id="orderTotal">Rp 0</strong>
                </div>
                <button type="button" class="submit-btn" id="submitOrder" disabled>Lanjut ke Pembayaran</button>
                <button type="button" class="reset-btn" id="resetOrder">Kosongkan Keranjang</button>
                <p class="panel-note" id="panelNote"></p>
            </div>
        </aside>

    </main>

    <div class="toast" id="toast"></div>

    <div class="pay-modal" id="paymentModal" hidden>
        <div class="pay-modal-backdrop" data-close-payment></div>
        <div class="pay-modal-card" role="dialog" aria-modal="true" aria-labelledby="paymentModalTitle">
            <div class="pay-modal-head">
                <h3 id="paymentModalTitle">Pembayaran</h3>
                <button type="button" class="pay-modal-close" data-close-payment aria-label="Tutup">&times;</button>
            </div>
            <div class="pay-modal-body">
                <div class="pay-summary" id="paymentSummary"></div>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cash" checked>
                        <div>
                            <strong>Tunai</strong>
                            <span>Pesanan disimpan dan pembayaran menunggu konfirmasi kasir.</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="transfer">
                        <div>
                            <strong>Transfer</strong>
                            <span>Pesanan disimpan dan pembayaran otomatis lunas.</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="qris">
                        <div>
                            <strong>QRIS</strong>
                            <span>Pesanan disimpan dan pembayaran otomatis lunas.</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="pay-modal-foot">
                <button type="button" class="reset-btn" data-close-payment>Kembali</button>
                <button type="button" class="submit-btn" id="confirmPaymentBtn">Bayar &amp; Simpan</button>
            </div>
        </div>
    </div>

    <script>
        const grid = document.getElementById('productGrid');
        const categoryBar = document.getElementById('categoryBar');
        const searchInput = document.getElementById('searchInput');
        const orderItemsEl = document.getElementById('orderItems');
        const orderTotalEl = document.getElementById('orderTotal');
        const tableInput = document.getElementById('tableNumber');
        const cartCountTag = document.getElementById('cartCountTag');
        const submitBtn = document.getElementById('submitOrder');
        const resetBtn = document.getElementById('resetOrder');
        const panelNote = document.getElementById('panelNote');
        const paymentModal = document.getElementById('paymentModal');
        const paymentSummaryEl = document.getElementById('paymentSummary');
        const confirmPayBtn = document.getElementById('confirmPaymentBtn');
        const toastEl = document.getElementById('toast');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const orderEndpoint = '{{ route('kasir.order.store') }}';
        const receiptBase = '{{ url('/kasir/order') }}';

        const cart = new Map();
        let activeCategory = 'all';
        let searchTerm = '';
        let lastKnownOrderId = null;

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

        function render() {
            let html = '';
            let total = 0;
            let count = 0;

            for (const item of cart.values()) {
                total += item.price * item.qty;
                count += item.qty;

                html += `
                    <div class="order-item">
                        <div class="oi-top">
                            <strong>${escapeHtml(item.name)}</strong>
                            <span>${rupiah(item.price * item.qty)}</span>
                        </div>
                        <div class="oi-bottom">
                            <div class="qty-control">
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

            orderItemsEl.innerHTML = html || '<p class="order-empty">Belum ada item. Pilih menu di sebelah kiri.</p>';
            orderTotalEl.textContent = rupiah(total);
            submitBtn.disabled = count === 0;
            cartCountTag.textContent = count + ' item';
            panelNote.textContent = count === 0 ? '' : count + ' item dalam keranjang';

            document.querySelectorAll('.product-card').forEach((card) => {
                const item = cart.get(card.dataset.id);
                const badge = card.querySelector('[data-card-qty]');
                const button = card.querySelector('[data-add]');

                if (item) {
                    badge.hidden = false;
                    badge.textContent = item.qty;
                    button.textContent = 'Tambah lagi';
                } else {
                    badge.hidden = true;
                    button.textContent = 'Tambah';
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

        function resetOrder() {
            cart.clear();
            tableInput.value = '';
            render();
        }

        grid.addEventListener('click', (event) => {
            const addButton = event.target.closest('[data-add]');
            if (!addButton) return;
            addToCart(addButton.closest('.product-card'));
        });

        orderItemsEl.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-action]');
            if (!button) return;

            const item = cart.get(button.dataset.id);
            if (!item) return;

            if (button.dataset.action === 'inc') {
                item.qty += 1;
            } else {
                item.qty -= 1;
                if (item.qty <= 0) cart.delete(item.id);
            }

            render();
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

        resetBtn.addEventListener('click', () => {
            if (cart.size === 0 && tableInput.value === '') return;
            resetOrder();
            showToast('Pesanan dikosongkan');
        });

        function selectedPaymentMethod() {
            const checked = paymentModal.querySelector('input[name="payment_method"]:checked');
            return checked ? checked.value : 'cash';
        }

        function setPaymentMethod(value) {
            const input = paymentModal.querySelector(`input[name="payment_method"][value="${value}"]`);
            if (input) input.checked = true;
        }

        function buildPaymentSummary(tableNumber) {
            let rows = '';
            let total = 0;
            let count = 0;

            for (const item of cart.values()) {
                const subtotal = item.price * item.qty;
                total += subtotal;
                count += item.qty;

                const levelLabel = item.spicy ? ` &middot; Level ${item.level}` : '';
                rows += `
                    <div class="pay-summary-row">
                        <span>${escapeHtml(item.name)}${levelLabel} &times; ${item.qty}</span>
                        <span>${rupiah(subtotal)}</span>
                    </div>
                `;
            }

            return `
                <div class="pay-summary-row">
                    <span>Meja / Pelanggan</span>
                    <span>${escapeHtml(tableNumber)}</span>
                </div>
                <div class="pay-summary-row">
                    <span>Jumlah Item</span>
                    <span>${count} item</span>
                </div>
                ${rows}
                <div class="pay-summary-row pay-summary-total">
                    <span>Total Bayar</span>
                    <strong>${rupiah(total)}</strong>
                </div>
            `;
        }

        function openPayment() {
            if (cart.size === 0) return;

            const tableNumber = tableInput.value.trim();
            if (tableNumber === '') {
                showToast('Nomor meja atau nama pelanggan wajib diisi', true);
                tableInput.focus();
                return;
            }

            paymentSummaryEl.innerHTML = buildPaymentSummary(tableNumber);
            paymentModal.hidden = false;
            document.body.style.overflow = 'hidden';
        }

        function closePayment() {
            paymentModal.hidden = true;
            document.body.style.overflow = '';
        }

        submitBtn.addEventListener('click', openPayment);

        paymentModal.querySelectorAll('[data-close-payment]').forEach((element) => {
            element.addEventListener('click', closePayment);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !paymentModal.hidden) closePayment();
        });

        confirmPayBtn.addEventListener('click', async () => {
            if (cart.size === 0) return;

            const tableNumber = tableInput.value.trim();
            const items = Array.from(cart.values()).map((item) => ({
                product_id: Number(item.id),
                name: item.name,
                price: item.price,
                qty: item.qty,
                spicy_level: item.spicy ? item.level : null
            }));
            const total = items.reduce((sum, item) => sum + item.price * item.qty, 0);
            const paymentMethod = selectedPaymentMethod();

            confirmPayBtn.disabled = true;
            confirmPayBtn.textContent = 'Memproses...';

            try {
                const response = await fetch(orderEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        table_number: tableNumber,
                        items: items,
                        total_amount: total,
                        payment_method: paymentMethod
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    const message = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal menyimpan pesanan.');
                    throw new Error(message);
                }

                closePayment();
                resetOrder();
                setPaymentMethod('cash');
                lastKnownOrderId = data.order_id;

                if (data.paid) {
                    showToast('Order #' + data.order_id + ' tersimpan dan pembayaran otomatis lunas.');
                } else {
                    showToast('Order #' + data.order_id + ' tersimpan. Pembayaran tunai menunggu konfirmasi.');
                }

                window.open(receiptBase + '/' + data.order_id + '/struk?auto=1', '_blank');
            } catch (error) {
                showToast(error.message, true);
            } finally {
                confirmPayBtn.disabled = false;
                confirmPayBtn.textContent = 'Bayar & Simpan';
            }
        });

        const paymentBadge = document.getElementById('paymentBadge');
        const notifEndpoint = '{{ route('kasir.notifications') }}';

        async function refreshPaymentNotifications(notify = true) {
            try {
                const response = await fetch(notifEndpoint, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) return;

                const data = await response.json();

                if (paymentBadge) {
                    paymentBadge.hidden = data.pending_payments <= 0;
                    paymentBadge.textContent = data.pending_payments;
                }

                if (notify && lastKnownOrderId !== null && data.latest_order_id > lastKnownOrderId) {
                    showToast('Pesanan baru #' + data.latest_order_id + ' masuk. Cek halaman Pembayaran.');
                }

                lastKnownOrderId = data.latest_order_id;
            } catch (error) {
                return;
            }
        }

        render();
        refreshPaymentNotifications(false);
        setInterval(() => refreshPaymentNotifications(true), 10000);
    </script>
</body>
</html>
