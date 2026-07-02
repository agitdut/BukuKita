<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BukuKita') }} - Perpustakaan Digital</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --library-green: #1b4332;
            --library-green-dark: #0d2818;
            --library-gold: #c9a227;
            --library-gold-light: #e0c46c;
            --library-cream: #f8f4e9;
        }
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--library-cream) 0%, #e8e0cc 50%, var(--library-cream) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        nav {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 1rem 2rem;
            gap: 1rem;
        }
        nav a {
            text-decoration: none;
            color: var(--library-green);
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        nav a:hover {
            background: rgba(27, 67, 50, 0.08);
        }
        nav a.btn-primary {
            background: var(--library-green);
            color: white;
        }
        nav a.btn-primary:hover {
            background: var(--library-green-dark);
        }
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        .hero-icon {
            width: 5rem;
            height: 5rem;
            background: linear-gradient(135deg, var(--library-green), var(--library-green-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--library-gold-light);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(27, 67, 50, 0.3);
        }
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--library-green-dark);
            margin-bottom: 0.75rem;
        }
        .hero p {
            font-size: 1.125rem;
            color: #6b7280;
            max-width: 28rem;
            margin-bottom: 2rem;
        }
        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .hero-actions a {
            text-decoration: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }
        .hero-actions .btn-primary {
            background: var(--library-green);
            color: white;
            box-shadow: 0 2px 10px rgba(27, 67, 50, 0.2);
        }
        .hero-actions .btn-primary:hover {
            background: var(--library-green-dark);
            transform: translateY(-1px);
        }
        .hero-actions .btn-outline {
            border: 2px solid var(--library-green);
            color: var(--library-green);
        }
        .hero-actions .btn-outline:hover {
            background: var(--library-green);
            color: white;
        }
        footer {
            text-align: center;
            padding: 1.5rem;
            color: #9ca3af;
            font-size: 0.8125rem;
        }
    </style>
</head>
<body>
    <nav>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endif
            @endauth
        @endif
    </nav>

    <div class="hero">
        <div class="hero-icon">B</div>
        <h1>BukuKita</h1>
        <p>Perpustakaan digital untuk mengelola koleksi buku, peminjaman, dan informasi perpustakaan dengan mudah.</p>
        <div class="hero-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Masuk ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">Mulai Sekarang</a>
                <a href="{{ route('register') }}" class="btn-outline">Buat Akun</a>
            @endauth
        </div>
    </div>

    <footer>&copy; {{ date('Y') }} BukuKita. All rights reserved.</footer>
</body>
</html>
