<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak | Barokah Mart</title>
    <style>
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #f3f4f6;
            color: #1f2937;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        main {
            width: min(100%, 480px);
            padding: 40px 32px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #fff;
            text-align: center;
            box-shadow: 0 12px 32px rgb(15 23 42 / 8%);
        }
        .code {
            margin: 0;
            color: #b91c1c;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .12em;
        }
        h1 { margin: 12px 0; font-size: clamp(26px, 6vw, 34px); }
        .message { margin: 0; color: #4b5563; line-height: 1.6; }
        a {
            display: inline-block;
            margin-top: 28px;
            padding: 11px 18px;
            border-radius: 8px;
            background: #166534;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
        }
        a:hover { background: #14532d; }
        a:focus-visible { outline: 3px solid #86efac; outline-offset: 3px; }
    </style>
</head>
<body>
    <main>
        <p class="code">ERROR 403</p>
        <h1>Akses ditolak</h1>
        <p class="message">Anda tidak memiliki izin untuk membuka halaman ini. Silakan kembali ke dashboard atau hubungi administrator toko.</p>
        <a href="{{ route('dashboard') }}">Kembali ke dashboard</a>
    </main>
</body>
</html>
