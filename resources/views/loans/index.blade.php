@extends('layouts.app')

@section('header')
    Daftar Peminjaman
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Data Peminjaman Buku</h3>
        <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Peminjaman
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari peminjam, judul buku, atau ISBN..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $loan->user->name }}</td>
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
                        @if($loan->fine > 0)
                            <span class="text-danger">Rp {{ number_format($loan->fine, 0, ',', '.') }}</span>
                        @else
                            <span class="text-success">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if($loan->status == 'borrowed')
                        <form action="{{ route('loans.return', $loan) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                <i class="fas fa-undo"></i> Kembalikan
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $loans->links() }}
    </div>
</div>
@endsection