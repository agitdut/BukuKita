@extends('layouts.app')

@section('header')
    Edit Buku
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Edit Buku</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('books.update', $book) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>ISBN *</label>
                <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn) }}" required>
                @error('isbn') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Judul *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}">
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $book->publisher) }}">
            </div>
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="text" name="published_year" class="form-control" value="{{ old('published_year', $book->published_year) }}">
            </div>
            <div class="form-group">
                <label>Cover Image URL</label>
                <input type="text" name="cover_image" class="form-control" value="{{ old('cover_image', $book->cover_image) }}">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image }}" class="mt-2" style="max-height:150px;">
                @endif
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="categories[]" class="form-control" multiple>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $book->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Tahan Ctrl untuk pilih lebih dari satu</small>
            </div>
            <div class="form-group">
                <label>Stok *</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $book->stock) }}" min="1" required>
                @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection