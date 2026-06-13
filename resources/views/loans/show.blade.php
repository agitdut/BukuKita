@extends('layouts.app')

@section('header')
    Detail Peminjaman
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Peminjaman #{{ $loan->id }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="30%">Peminjam</th>
                <td>{{ $loan->user->name }}</td>
            </tr>
            <tr>
                <th>Buku</th>
                <td>{{ $loan->book->title }}</td>
            </tr>
            <tr>
                <th>ISBN</th>
                <td>{{ $loan->book->isbn }}</td>
            </tr>
            <tr>
                <th>Tanggal Pinjam</th>
                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Tanggal Harus Kembali</th>
                <td>{{ $loan->due_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Tanggal Dikembalikan</th>
                <td>{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
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
            <tr>
                <th>Denda</th>
                <td>
                    @if($loan->fine > 0)
                        <span class="text-danger font-weight-bold">
                            Rp {{ number_format($loan->fine, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="text-success">Tidak ada denda</span>
                    @endif
                </td>
            </tr>
        </table>

        <a href="{{ route('loans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        @if($loan->status == 'borrowed')
        <form action="{{ route('loans.return', $loan) }}" method="POST" class="d-inline"
            onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
            @csrf
            <button class="btn btn-success">
                <i class="fas fa-undo"></i> Kembalikan Buku
            </button>
        </form>
        @endif
    </div>
</div>
@endsection