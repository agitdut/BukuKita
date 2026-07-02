<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $books = Book::with('categories')
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('books.index', compact('books', 'search'));
}

    public function show(Book $book)
    {
        $book->load('categories');
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'isbn'        => 'required|unique:books,isbn',
            'title'       => 'required',
            'stock'       => 'required|integer|min:1',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $book = Book::create($request->all());

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        $book->load('categories');
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'isbn'        => 'required|unique:books,isbn,' . $book->id,
            'title'       => 'required',
            'stock'       => 'required|integer|min:1',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $book->update($request->all());

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        } else {
            $book->categories()->detach();
        }

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    public function fetchByIsbn(Request $request)
    {
        $isbn = $request->input('isbn');

        $response = Http::withOptions([
            'verify' => false,
        ])->get('https://openlibrary.org/api/books', [
            'bibkeys' => 'ISBN:' . $isbn,
            'jscmd'   => 'data',
            'format'  => 'json',
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data'], 500);
        }

        $data = $response->json();
        $key = 'ISBN:' . $isbn;
        $bookData = $data[$key] ?? null;

        if (!$bookData) {
            return response()->json(['error' => 'Buku tidak ditemukan'], 404);
        }

        $details = $bookData['details'] ?? $bookData;
        $desc = '';
        if (isset($details['description'])) {
            $desc = is_array($details['description'])
                ? ($details['description']['value'] ?? '')
                : $details['description'];
        }

        $cover = '';
        if (!empty($bookData['cover']['medium'])) {
            $cover = $bookData['cover']['medium'];
        }

        return response()->json([
            'title'          => $details['title'] ?? '',
            'author'         => $details['authors'][0]['name'] ?? '',
            'publisher'      => $details['publishers'][0]['name'] ?? '',
            'published_year' => $details['publish_date']
                ? (preg_match('/\d{4}/', $details['publish_date'], $matches) ? $matches[0] : $details['publish_date'])
                : '',
            'description'    => $desc,
            'cover_image'    => $cover,
        ]);
    }
}