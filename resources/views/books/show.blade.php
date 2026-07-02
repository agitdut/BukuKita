@extends('layouts.app')

@section('header')
    Detail Buku
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image }}" class="img-fluid" style="max-height:400px;">
                @else
                    <div class="py-5 text-muted">
                        <i class="fas fa-book fa-4x"></i>
                        <p class="mt-2">No Cover</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $book->title }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:200px;">ISBN</th>
                        <td>{{ $book->isbn }}</td>
                    </tr>
                    <tr>
                        <th>Penulis</th>
                        <td>{{ $book->author ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Penerbit</th>
                        <td>{{ $book->publisher ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>{{ $book->published_year ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>
                            @forelse($book->categories as $category)
                                <span class="badge badge-info">{{ $category->name }}</span>
                            @empty
                                <span class="badge badge-secondary">Tidak ada kategori</span>
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>
                            <span class="badge {{ $book->stock > 0 ? 'badge-success' : 'badge-danger' }}">
                                {{ $book->stock }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $book->description ?? '-' }}</td>
                    </tr>
                </table>

                <div class="mt-3">
                    <a href="{{ route('books.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @role('admin|staff')
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
