<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BukuKita') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --library-green: #1b4332;
            --library-green-dark: #0d2818;
            --library-gold: #c9a227;
            --library-gold-light: #e0c46c;
            --library-cream: #f8f4e9;
        }
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--library-cream) 0%, #e8e0cc 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            width: 100%;
            max-width: 28rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 20px rgba(27, 67, 50, 0.15);
            border-top: 4px solid var(--library-gold);
            padding: 2rem;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .auth-logo .logo-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--library-green), var(--library-green-dark));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--library-gold-light);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .auth-logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--library-green);
            margin: 0;
        }
        .auth-logo p {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <div class="logo-icon">B</div>
            <h1>BukuKita</h1>
            <p>Perpustakaan Digital</p>
        </div>
        {{ $slot }}
    </div>
    <p class="mt-4 text-sm text-gray-500">&copy; {{ date('Y') }} BukuKita. All rights reserved.</p>
</body>
</html>
