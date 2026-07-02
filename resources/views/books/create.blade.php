@extends('layouts.app')

@section('header')
    Tambah Buku
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Buku</h3>
    </div>
    <div class="card-body">

        {{-- Form cari ISBN --}}
        <div class="form-group">
            <label>Cari Buku via ISBN</label>
            <div class="input-group">
                <input type="text" id="isbn_search" class="form-control" placeholder="Masukkan ISBN...">
                <div class="input-group-append">
                    <button class="btn btn-info" onclick="fetchIsbn()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
            <small class="text-muted">Data akan terisi otomatis dari Open Library</small>
        </div>

        <hr>

        <form action="{{ route('books.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>ISBN *</label>
                <input type="text" name="isbn" id="isbn" class="form-control" value="{{ old('isbn') }}" required>
                @error('isbn') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Judul *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="author" id="author" class="form-control" value="{{ old('author') }}">
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="publisher" id="publisher" class="form-control" value="{{ old('publisher') }}">
            </div>
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="text" name="published_year" id="published_year" class="form-control" value="{{ old('published_year') }}">
            </div>
            <div class="form-group">
                <label>Cover Image URL</label>
                <input type="text" name="cover_image" id="cover_image" class="form-control" value="{{ old('cover_image') }}">
                <div class="mt-2">
                    <img id="cover_preview" src="" style="max-height:150px; display:none;">
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="categories[]" class="form-control" multiple>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Tahan Ctrl untuk pilih lebih dari satu</small>
            </div>
            <div class="form-group">
                <label>Stok *</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', 1) }}" min="1" required>
                @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
function fetchIsbn() {
    const isbn = document.getElementById('isbn_search').value;
    if (!isbn) return alert('Masukkan ISBN terlebih dahulu!');

    fetch(`/books/fetch-isbn?isbn=${isbn}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) return alert(data.error);

            document.getElementById('isbn').value           = isbn;
            document.getElementById('title').value          = data.title;
            document.getElementById('author').value         = data.author;
            document.getElementById('publisher').value      = data.publisher;
            document.getElementById('published_year').value = data.published_year;
            document.getElementById('description').value    = data.description;
            document.getElementById('cover_image').value    = data.cover_image;

            const preview = document.getElementById('cover_preview');
            if (data.cover_image) {
                preview.src = data.cover_image;
                preview.style.display = 'block';
            }
        })
        .catch(() => alert('Gagal mengambil data!'));
}
</script>
@endsection