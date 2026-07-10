@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')

    @role('admin|staff')
    @if($overdueLoans->count() > 0)
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian! Ada {{ $overdueLoans->count() }} buku yang terlambat dikembalikan:</h5>
        <ul class="mb-0 mt-2">
            @foreach($overdueLoans->take(5) as $loan)
            <li>
                <strong>{{ $loan->book->title }}</strong> dipinjam oleh <strong>{{ $loan->user->name }}</strong>
                — jatuh tempo {{ $loan->due_date->format('d/m/Y') }}
                ({{ $loan->due_date->diffInDays(\Carbon\Carbon::today()) }} hari terlambat)
            </li>
            @endforeach
        </ul>
        @if($overdueLoans->count() > 5)
        <small class="text-muted">dan {{ $overdueLoans->count() - 5 }} lainnya...</small>
        @endif
    </div>
    @endif
    @endrole

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalBooks }}</h3>
                    <p>Total Buku</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('books.index') }}" class="small-box-footer">
                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        @role('admin|staff')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalLoans }}</h3>
                    <p>Total Peminjaman</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <a href="{{ route('loans.index') }}" class="small-box-footer">
                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $activeLoans }}</h3>
                    <p>Sedang Dipinjam</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('loans.index') }}" class="small-box-footer">
                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endrole

        @role('admin')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total Anggota</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('users.index') }}" class="small-box-footer">
                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endrole

        @role('member')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $myLoans->where('status', 'borrowed')->count() }}</h3>
                    <p>Buku Sedang Dipinjam</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endrole
    </div>

    <div class="row">
        @role('admin|staff')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Peminjaman Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLoans as $loan)
                            <tr>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->book->title }}</td>
                                <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($loan->status == 'borrowed')
                                        <span class="badge badge-warning">Dipinjam</span>
                                    @elseif($loan->status == 'returned')
                                        <span class="badge badge-success">Dikembalikan</span>
                                    @else
                                        <span class="badge badge-danger">Terlambat</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada peminjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endrole

        @role('member')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i> Riwayat Peminjaman Saya</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myLoans as $loan)
                            <tr>
                                <td>{{ $loan->book->title }}</td>
                                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($loan->status == 'borrowed')
                                        <span class="badge badge-warning">Dipinjam</span>
                                    @elseif($loan->status == 'returned')
                                        <span class="badge badge-success">Dikembalikan</span>
                                    @else
                                        <span class="badge badge-danger">Terlambat</span>
                                    @endif
                                </td>
                                <td>
                                    @php $fineDisplay = $loan->currentFine(); @endphp
                                    @if($fineDisplay > 0)
                                        <span class="text-danger">Rp {{ number_format($fineDisplay, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-success">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Anda belum pernah meminjam buku.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endrole

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buku Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($recentBooks as $book)
                        <li class="item">
                            <div class="product-info">
                                @role('admin|staff')
                                <a href="{{ route('books.edit', $book) }}" class="product-title">
                                    {{ $book->title }}
                                </a>
                                @else
                                <span class="product-title">{{ $book->title }}</span>
                                @endrole
                                <span class="product-description">
                                    {{ $book->author }} | Stok: {{ $book->stock }}
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="item">
                            <div class="product-info">Belum ada buku.</div>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection