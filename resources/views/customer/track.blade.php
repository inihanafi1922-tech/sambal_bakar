<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1c120e">
    <title>Lacak Pesanan - Sambal Bakar</title>
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
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        .topbar { background: rgba(28, 18, 14, .97); color: #fff; }

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
        }

        .brand h1 { font-size: 16px; margin: 0; font-weight: 800; }
        .brand p { margin: 2px 0 0; font-size: 11px; color: #d8c7bd; }

        main { width: min(560px, 100% - 28px); margin: 18px auto 40px; }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 18px 40px -24px rgba(60, 30, 10, .35);
            margin-bottom: 14px;
        }

        h2 { margin: 0 0 6px; font-size: 18px; font-weight: 800; }

        .sub { margin: 0 0 14px; font-size: 13px; color: var(--muted); }

        .barcode-box {
            display: flex;
            justify-content: center;
            padding: 12px;
            border: 1px dashed var(--line);
            border-radius: 14px;
            background: #fdfaf6;
        }

        .barcode-box svg { max-width: 100%; }

        .code-text { text-align: center; font-weight: 800; letter-spacing: 2px; margin: 10px 0 14px; font-size: 15px; }

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

        .pay-status { margin: 12px 0 0; font-size: 13px; color: var(--muted); }
        .done-note { margin: 12px 0 0; font-size: 12px; color: var(--muted); line-height: 1.6; }

        .items { display: flex; flex-direction: column; gap: 10px; }

        .item-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            font-size: 13px;
            border-bottom: 1px dashed #f0e6db;
            padding-bottom: 9px;
        }
        .item-row:last-of-type { border-bottom: 0; }
        .item-row strong { font-weight: 700; }
        .item-note { color: #9a5b00; font-size: 11px; font-weight: 700; }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed var(--line);
            font-size: 14px;
            font-weight: 700;
        }
        .total-row strong { font-size: 19px; color: var(--brand); }

        .form-input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 13px 14px;
            font-size: 15px;
            font-weight: 700;
            background: #fdfaf6;
            color: var(--ink);
            outline: none;
            margin-bottom: 10px;
        }
        .form-input:focus { border-color: var(--accent); }

        .submit-btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: var(--brand);
            color: #fff;
            padding: 13px;
            font-size: 15px;
            font-weight: 800;
        }

        .alert-error {
            padding: 12px 14px;
            border-radius: 12px;
            background: #fdecec;
            border: 1px solid #f3c7c7;
            color: #a61b1b;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .back-link {
            display: block;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--brand);
            text-decoration: none;
            margin-top: 6px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <span class="brand-mark">SB</span>
            <div class="brand">
                <h1>Sambal Bakar</h1>
                <p>Lacak Pesanan</p>
            </div>
        </div>
    </header>

    <main>
        @if ($order)
            <section class="card">
                <h2>Pesanan #{{ $order->id }}</h2>
                <p class="sub">{{ $order->table_number }} &middot; {{ $order->created_at->format('d/m/Y H:i') }}</p>

                <div class="barcode-box"><svg id="trackBarcode"></svg></div>
                <p class="code-text">{{ $order->tracking_code }}</p>

                <span class="status-pill" id="statusPill"><span class="status-dot"></span><span id="statusText">{{ $order->statusLabel() }}</span></span>
                <p class="pay-status" id="payStatus">{{ $order->isPaid() ? 'Pembayaran sudah dikonfirmasi kasir.' : 'Pembayaran menunggu konfirmasi kasir.' }}</p>
                <p class="done-note" id="doneNote">Halaman ini diperbarui otomatis setiap 5 detik.</p>
            </section>

            <section class="card">
                <h2>Rincian Pesanan</h2>
                <div class="items">
                    @foreach ($order->items as $item)
                        <div class="item-row">
                            <div>
                                <strong>{{ $item['name'] ?? '-' }}</strong>
                                @if (! empty($item['spicy_level']))
                                    <div class="item-note">Level pedas: {{ $item['spicy_level'] }}</div>
                                @endif
                            </div>
                            <span>&times; {{ $item['qty'] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="total-row">
                    <span>Total ({{ $order->paymentMethodLabel() }})</span>
                    <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                </div>
            </section>
        @else
            <section class="card">
                <h2>Lacak Pesanan</h2>
                <p class="sub">Masukkan kode lacak dari struk atau layar pesanan kamu.</p>

                @if (isset($code) && $code !== '')
                    <div class="alert-error">Pesanan dengan kode "{{ $code }}" tidak ditemukan.</div>
                @endif

                <form method="GET" action="{{ url('/lacak') }}">
                    <input type="text" name="code" class="form-input" placeholder="Contoh: SB7K2QX" autocomplete="off" required>
                    <button type="submit" class="submit-btn">Cari Pesanan</button>
                </form>
            </section>
        @endif

        <a href="{{ route('pelanggan.menu') }}" class="back-link">Pesan menu lagi</a>
    </main>

    @if ($order)
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
        <script>
            const statusUrl = '{{ route('pelanggan.track.status', $order->tracking_code) }}';
            const statusText = document.getElementById('statusText');
            const statusPill = document.getElementById('statusPill');
            const payStatus = document.getElementById('payStatus');
            const doneNote = document.getElementById('doneNote');

            try {
                if (window.JsBarcode) {
                    JsBarcode('#trackBarcode', '{{ $order->tracking_code }}', { width: 2, height: 64, displayValue: false, margin: 0 });
                }
            } catch (error) {}

            let pollTimer = null;

            async function refresh() {
                try {
                    const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) return;

                    const data = await response.json();
                    statusText.textContent = data.status_label;
                    statusPill.classList.toggle('done', data.status === 'served');
                    payStatus.textContent = data.paid
                        ? 'Pembayaran sudah dikonfirmasi kasir.'
                        : 'Pembayaran menunggu konfirmasi kasir.';

                    if (data.status === 'served') {
                        doneNote.textContent = 'Pesanan sudah selesai disajikan. Selamat menikmati!';
                        if (pollTimer) clearInterval(pollTimer);
                    }
                } catch (error) {
                    return;
                }
            }

            refresh();
            pollTimer = setInterval(refresh, 5000);
        </script>
    @endif
</body>
</html>
