<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1c120e">
    <title>Masuk - Sambal Bakar</title>
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
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background:
                radial-gradient(circle at 15% 15%, rgba(247, 127, 0, .18), transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(214, 40, 40, .16), transparent 45%),
                var(--bg);
            color: var(--ink);
        }

        .login-card {
            width: min(430px, 100%);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 34px;
            box-shadow: 0 30px 70px -40px rgba(60, 30, 10, .55);
        }

        .brand { display: flex; align-items: center; gap: 13px; margin-bottom: 26px; }

        .brand-mark {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: linear-gradient(135deg, var(--accent), var(--brand));
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand h1 { margin: 0; font-size: 19px; font-weight: 800; }
        .brand p { margin: 2px 0 0; font-size: 13px; color: var(--muted); }

        .login-card h2 { margin: 0 0 6px; font-size: 22px; font-weight: 800; }
        .login-card .subtitle { margin: 0 0 22px; font-size: 13px; color: var(--muted); line-height: 1.5; }

        .field { margin-bottom: 15px; }

        .field label { display: block; margin-bottom: 7px; font-size: 12px; font-weight: 700; color: var(--muted); }

        .field input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            background: #fdfaf6;
            color: var(--ink);
            outline: none;
        }

        .field input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(247, 127, 0, .15); }

        .remember { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); margin: 4px 0 20px; }
        .remember input { width: 16px; height: 16px; accent-color: var(--brand); }

        .submit-btn {
            width: 100%;
            border: 0;
            border-radius: 13px;
            background: var(--brand);
            color: #fff;
            padding: 13px;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            transition: background .18s ease;
        }

        .submit-btn:hover { background: var(--brand-dark); }

        .alert {
            background: #fde8e8;
            color: #a61b1b;
            border: 1px solid #f5c2c2;
            padding: 11px 13px;
            border-radius: 11px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .hint {
            margin-top: 22px;
            padding: 14px;
            border-radius: 12px;
            background: #fdf6ee;
            border: 1px dashed var(--line);
            font-size: 12px;
            color: var(--muted);
            line-height: 1.7;
        }

        .hint strong { color: var(--ink); }

        .back { display: block; margin-top: 18px; text-align: center; font-size: 13px; color: var(--muted); text-decoration: none; }
        .back:hover { color: var(--brand); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <span class="brand-mark">SB</span>
            <div>
                <h1>Sambal Bakar</h1>
                <p>Sistem Pesanan Restoran</p>
            </div>
        </div>

        <h2>Masuk</h2>
        <p class="subtitle">Halaman ini khusus admin dan kasir. Pelanggan cukup membuka menu tanpa login.</p>

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>

            <button type="submit" class="submit-btn">Masuk</button>
        </form>

        <div class="hint">
            <strong>Akun demo</strong><br>
            Admin: admin@sambalbakar.test / password<br>
            Kasir: kasir@sambalbakar.test / password
        </div>

    </div>
</body>
</html>
