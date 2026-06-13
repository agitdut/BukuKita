@extends('layouts.app')

@section('header')
    Tambah Peminjaman
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Peminjaman Buku</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Peminjam *</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Buku *</label>
                <select name="book_id" class="form-control" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} (Stok: {{ $book->stock }})
                        </option>
                    @endforeach
                </select>
                @error('book_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Tanggal Pinjam *</label>
                <input type="date" name="loan_date" class="form-control"
                    value="{{ old('loan_date', date('Y-m-d')) }}" required>
                @error('loan_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Tanggal Harus Kembali *</label>
                <input type="date" name="due_date" class="form-control"
                    value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection