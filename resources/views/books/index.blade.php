@extends('layouts.app')

@section('header')
    Daftar Buku
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Data Buku Perpustakaan</h3>
            @role('admin|staff')
            <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Buku
            </a>
            @endrole
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Search Form -->
            <form action="{{ route('books.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari judul, penulis, atau ISBN..."
                        value="{{ $search }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        @if($search)
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cover</th>
                        <th>ISBN</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        @role('admin|staff')
                        <th>Aksi</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image }}" width="50">
                            @else
                                <span class="badge badge-secondary">No Cover</span>
                            @endif
                        </td>
                        <td>{{ $book->isbn }}</td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="text-[#1b4332] font-weight-bold">
                                {{ $book->title }}
                            </a>
                        </td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->publisher }}</td>
                        <td>
                            @forelse($book->categories as $category)
                                <span class="badge badge-info">{{ $category->name }}</span>
                            @empty
                                <span class="badge badge-secondary">-</span>
                            @endforelse
                        </td>
                        <td>
                            <span class="badge {{ $book->stock > 0 ? 'badge-success' : 'badge-danger' }}">
                                {{ $book->stock }}
                            </span>
                        </td>
                        @role('admin|staff')
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            @role('admin')
                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endrole
                        </td>
                        @endrole
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $books->links() }}
        </div>
    </div>
@endsection