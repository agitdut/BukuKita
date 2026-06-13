<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    // Halaman daftar buku
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    // Halaman form tambah buku
    public function create()
    {
        return view('books.create');
    }

    // Simpan buku baru
    public function store(Request $request)
    {
        $request->validate([
            'isbn'  => 'required|unique:books,isbn',
            'title' => 'required',
            'stock' => 'required|integer|min:1',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    // Halaman edit buku
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    // Update buku
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'isbn'  => 'required|unique:books,isbn,' . $book->id,
            'title' => 'required',
            'stock' => 'required|integer|min:1',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diupdate!');
    }

    // Hapus buku
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    // Endpoint untuk fetch data dari Google Books API berdasarkan ISBN
    public function fetchByIsbn(Request $request)
    {
        $isbn = $request->input('isbn');

        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'q' => 'isbn:' . $isbn,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data'], 500);
        }

        $data = $response->json();

        if (empty($data['items'])) {
            return response()->json(['error' => 'Buku tidak ditemukan'], 404);
        }

        $info = $data['items'][0]['volumeInfo'];

        return response()->json([
            'title'          => $info['title'] ?? '',
            'author'         => isset($info['authors']) ? implode(', ', $info['authors']) : '',
            'publisher'      => $info['publisher'] ?? '',
            'published_year' => isset($info['publishedDate']) ? substr($info['publishedDate'], 0, 4) : '',
            'description'    => $info['description'] ?? '',
            'cover_image'    => $info['imageLinks']['thumbnail'] ?? '',
        ]);
    }
}