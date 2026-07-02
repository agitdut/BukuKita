<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'BukuKita') }}</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

  <style>
    :root {
      --library-green: #1b4332;
      --library-green-dark: #0d2818;
      --library-gold: #c9a227;
      --library-gold-light: #e0c46c;
      --library-cream: #f8f4e9;
    }

    body {
      background-color: var(--library-cream) !important;
    }

    .main-sidebar {
      background: linear-gradient(180deg, var(--library-green) 0%, var(--library-green-dark) 100%) !important;
    }

    .brand-link {
      background-color: var(--library-green-dark) !important;
      border-bottom: 2px solid var(--library-gold) !important;
    }

    .brand-text {
      color: var(--library-gold-light) !important;
      font-weight: 600 !important;
    }

    .nav-sidebar > .nav-item > .nav-link.active {
      background-color: var(--library-gold) !important;
      color: var(--library-green-dark) !important;
      font-weight: 600;
    }

    .nav-sidebar .nav-link {
      color: #e8e8e8 !important;
    }

    .nav-sidebar .nav-link:hover {
      background-color: rgba(201, 162, 39, 0.2) !important;
    }

    .nav-icon {
      color: var(--library-gold-light) !important;
    }

    .nav-link.active .nav-icon {
      color: var(--library-green-dark) !important;
    }

    .main-header.navbar {
      background-color: white !important;
      border-bottom: 3px solid var(--library-gold) !important;
    }

    .card {
      border-top: 3px solid var(--library-green) !important;
      box-shadow: 0 2px 8px rgba(27, 67, 50, 0.1) !important;
    }

    .card-header {
      background-color: rgba(27, 67, 50, 0.03) !important;
    }

    .card-title {
      color: var(--library-green) !important;
    }

    .btn-primary {
      background-color: var(--library-green) !important;
      border-color: var(--library-green) !important;
    }

    .btn-primary:hover {
      background-color: var(--library-green-dark) !important;
      border-color: var(--library-green-dark) !important;
    }

    .btn-warning {
      background-color: var(--library-gold) !important;
      border-color: var(--library-gold) !important;
      color: white !important;
    }

    .small-box.bg-info {
      background-color: var(--library-green) !important;
    }

    .small-box.bg-success {
      background-color: #2d6a4f !important;
    }

    .small-box.bg-warning {
      background-color: var(--library-gold) !important;
    }

    .small-box.bg-danger {
      background-color: #8b4513 !important;
    }

    .badge-success {
      background-color: #2d6a4f !important;
    }

    .badge-warning {
      background-color: var(--library-gold) !important;
    }

    .badge-info {
      background-color: #2d6a4f !important;
    }

    .btn-info {
      background-color: #2d6a4f !important;
      border-color: #2d6a4f !important;
      color: white !important;
    }

    .btn-info:hover {
      background-color: #1b4332 !important;
      border-color: #1b4332 !important;
    }

    .table thead th {
      background-color: var(--library-green) !important;
      color: white !important;
      border: none !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(201, 162, 39, 0.05) !important;
    }

    .main-footer {
      background-color: white !important;
      border-top: 2px solid var(--library-gold) !important;
      color: var(--library-green) !important;
    }

    .breadcrumb-item.active {
      color: var(--library-gold) !important;
    }

    .user-panel {
      border-bottom: 1px solid rgba(201, 162, 39, 0.3) !important;
    }
  </style>

  @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
  </div>

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          <span class="ml-1">{{ auth()->user()->name }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="{{ route('profile.edit') }}" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">BukuKita</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name }}</a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          {{-- Dashboard - Semua role --}}
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          {{-- Daftar Buku - Semua role --}}
          <li class="nav-item">
            <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>Daftar Buku</p>
            </a>
          </li>

          {{-- Peminjaman - Admin & Staff --}}
          @role('admin|staff')
          <li class="nav-item">
            <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hand-holding-heart"></i>
              <p>Peminjaman</p>
            </a>
          </li>
          @endrole

          {{-- Manajemen User - Hanya Admin --}}
          @role('admin')
          <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen User</p>
            </a>
          </li>
          @endrole

          {{-- AI Assistant - Semua role --}}
          <li class="nav-item">
            <a href="{{ route('chat.index') }}" class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-robot"></i>
              <p>AI Assistant</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('header')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">@yield('header')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2025 BukuKita.</strong> All rights reserved.
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.js') }}"></script>

@stack('js')
</body>
</html>