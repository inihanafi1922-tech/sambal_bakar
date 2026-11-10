<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1c120e">
    <title>@yield('title', 'Sambal Bakar')</title>
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
            --shadow: 0 18px 40px -24px rgba(60, 30, 10, .4);
        }

        * { box-sizing: border-box; }

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
            width: min(1180px, 100% - 32px);
            margin-inline: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 0;
            flex-wrap: wrap;
        }

        .brand { display: flex; align-items: center; gap: 11px; }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--brand));
            display: grid;
            place-items: center;
            font-weight: 800;
        }

        .brand h1 { font-size: 16px; margin: 0; font-weight: 800; }
        .brand p { margin: 0; font-size: 11px; color: #d8c7bd; }

        .topnav { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

        .topnav a {
            display: inline-flex;
            align-items: center;
            padding: 8px 13px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: #e7d8cf;
            border: 1px solid transparent;
            transition: .15s ease;
        }

        .topnav a:hover { background: rgba(255, 255, 255, .1); }

        .topnav a.active {
            background: var(--accent);
            color: #231a15;
            border-color: var(--accent);
        }

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

        .user-box { display: flex; align-items: center; gap: 10px; }

        .user-meta { text-align: right; line-height: 1.2; }
        .user-meta strong { display: block; font-size: 13px; }
        .user-meta span { font-size: 11px; color: #d8c7bd; text-transform: capitalize; }

        .logout-btn {
            border: 1px solid rgba(255, 255, 255, .2);
            background: transparent;
            color: #fff;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .logout-btn:hover { background: rgba(255, 255, 255, .12); }

        .page {
            width: min(1180px, 100% - 32px);
            margin: 26px auto 48px;
        }

        .page-head { margin-bottom: 20px; }
        .page-head h2 { margin: 0; font-size: 24px; font-weight: 800; }
        .page-head p { margin: 6px 0 0; color: var(--muted); font-size: 14px; }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .stat {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .stat span { display: block; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
        .stat strong { display: block; margin-top: 8px; font-size: 26px; font-weight: 800; }
        .stat.accent strong { color: var(--brand); }

        .section-title { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 0 0 12px; }
        .section-title h3 { margin: 0; font-size: 17px; font-weight: 800; }
        .section-title a { font-size: 13px; font-weight: 700; color: var(--brand); text-decoration: none; }
        .section-title a:hover { text-decoration: underline; }

        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            text-align: left;
            padding: 12px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
            border-bottom: 1px solid var(--line);
            white-space: nowrap;
        }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #f4ece4; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #fdfaf6; }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }
        .badge.pending { background: #fdecec; color: #a61b1b; }
        .badge.cooking { background: #fff3e0; color: #9a5b00; }
        .badge.served { background: #e6f4ea; color: #1e7a3d; }

        .money { font-weight: 700; color: var(--brand); }
        .muted { color: var(--muted); }
        .empty-row { padding: 30px; text-align: center; color: var(--muted); }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .filter-form label { display: block; font-size: 11px; font-weight: 700; color: var(--muted); margin-bottom: 6px; }
        .filter-form input {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            background: #fdfaf6;
            color: var(--ink);
        }
        .btn {
            border: 0;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 700;
        }
        .btn:hover { background: var(--brand-dark); }
        .btn.dark { background: #231a15; }
        .btn.dark:hover { background: #3a2c24; }
        .btn.ghost { background: #efe4d8; color: #231a15; }
        .btn.ghost:hover { background: #e6d6c5; }
        .btn.small { padding: 7px 11px; font-size: 12px; }
        .btn.danger { background: #fbe4e4; color: #a61b1b; }
        .btn.danger:hover { background: #f6d0d0; }
        .btn-row { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

        .alert {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: 12px;
            background: #e6f4ea;
            border: 1px solid #bfe3c9;
            color: #1e7a3d;
            font-size: 13px;
            font-weight: 600;
        }
        .alert.error {
            background: #fdecec;
            border-color: #f3c7c7;
            color: #a61b1b;
        }

        .form-card { padding: 20px; margin-bottom: 22px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; }
        .form-grid .span-all { grid-column: 1 / -1; }
        .field label { display: block; font-size: 11px; font-weight: 700; color: var(--muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .4px; }
        .field input,
        .field select,
        .field textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            font-family: inherit;
            background: #fdfaf6;
            color: var(--ink);
            outline: none;
        }
        .field input:focus,
        .field select:focus,
        .field textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(247, 127, 0, .15); }
        .field .hint { display: block; margin-top: 5px; font-size: 11px; color: var(--muted); }
        .field-check { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; }
        .field-check input { width: auto; }

        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px; }

        .thumb {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            object-fit: cover;
            background: linear-gradient(135deg, #fde7c8, #f6c39a);
            display: grid;
            place-items: center;
            color: #7a3b12;
            font-weight: 800;
            font-size: 18px;
            overflow: hidden;
        }

        .badge.income { background: #e6f4ea; color: #1e7a3d; }
        .badge.expense { background: #fdecec; color: #a61b1b; }
        .money.income { color: #1e7a3d; }
        .money.expense { color: #a61b1b; }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f3e7d8;
            color: #6b4a2f;
            font-size: 11px;
            font-weight: 700;
        }

        .table-actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .inline-form { display: inline; margin: 0; }

        .qris-preview {
            display: inline-flex;
            padding: 10px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fdfaf6;
        }
        .qris-preview img { width: 180px; height: 180px; object-fit: contain; border-radius: 8px; display: block; }

        .chart-card { padding: 18px 20px 8px; }
        .chart-card h3 { margin: 0 0 4px; font-size: 16px; font-weight: 800; }
        .chart-card p { margin: 0 0 12px; font-size: 12px; color: var(--muted); }
        .chart-box { position: relative; height: 280px; }

        .date-heading { font-size: 13px; font-weight: 800; margin: 18px 0 8px; color: var(--ink); }
        .date-heading span { color: var(--muted); font-weight: 600; }
        .staff-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 11px 0; border-bottom: 1px solid #f4ece4; }
        .staff-row:last-child { border-bottom: 0; }
        .staff-name { font-weight: 700; font-size: 14px; }
        .staff-meta { font-size: 12px; color: var(--muted); }

        .filter-form select {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            background: #fdfaf6;
            color: var(--ink);
        }

        @media (max-width: 760px) {
            .user-meta { display: none; }
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
                    <p>@yield('subtitle', 'Sistem Pesanan')</p>
                </div>
            </div>

            <nav class="topnav">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Staf</a>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Pengaturan Menu</a>
                    <a href="{{ route('admin.payment-settings') }}" class="{{ request()->routeIs('admin.payment-settings*') ? 'active' : '' }}">Pembayaran</a>
                    <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">Laporan Keuangan</a>
                @else
                    <a href="{{ route('kasir.staff') }}" class="{{ request()->routeIs('kasir.staff*') ? 'active' : '' }}">Staf</a>
                    <a href="{{ route('kasir.index') }}" class="{{ request()->routeIs('kasir.index') ? 'active' : '' }}">Kasir</a>
                    <a href="{{ route('kasir.payments') }}" class="{{ request()->routeIs('kasir.payments') ? 'active' : '' }}">Pembayaran <span class="notif-badge" id="paymentBadge" hidden></span></a>
                @endif
                <a href="{{ route('password.edit') }}" class="{{ request()->routeIs('password.*') ? 'active' : '' }}">Password</a>
            </nav>

            <div class="user-box">
                <div class="user-meta">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->role }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="page">
        @yield('content')
    </main>

    <div class="toast" id="toast"></div>

    @unless (auth()->user()->isAdmin())
        <script>
            const paymentBadge = document.getElementById('paymentBadge');
            const toastEl = document.getElementById('toast');
            const notifEndpoint = '{{ route('kasir.notifications') }}';
            let lastKnownOrderId = null;

            function showNotifToast(message) {
                if (!toastEl) return;
                toastEl.textContent = message;
                toastEl.classList.add('show');
                clearTimeout(showNotifToast.timer);
                showNotifToast.timer = setTimeout(() => toastEl.classList.remove('show'), 4000);
            }

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
                        showNotifToast('Pesanan baru #' + data.latest_order_id + ' masuk. Cek halaman Pembayaran.');
                    }

                    lastKnownOrderId = data.latest_order_id;
                } catch (error) {
                    return;
                }
            }

            refreshPaymentNotifications(false);
            setInterval(() => refreshPaymentNotifications(true), 10000);
        </script>
    @endunless

    @stack('scripts')
</body>
</html>
