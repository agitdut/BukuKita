<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $books = Book::when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('books.index', compact('books', 'search'));
}

    public function create()
    {
        return view('books.create');
    }

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

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

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

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    public function fetchByIsbn(Request $request)
    {
        $isbn = $request->input('isbn');
        $apiKey = env('GROQ_API_KEY');

        $response = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model'    => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => 'You are a book database assistant. When given an ISBN, return ONLY a valid JSON object with these fields: title, author, publisher, published_year, description. No explanation, no markdown, just pure JSON.'
                ],
                [
                    'role'    => 'user',
                    'content' => 'Give me book information for ISBN: ' . $isbn
                ]
            ],
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data'], 500);
        }

        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? null;

        if (!$text) {
            return response()->json(['error' => 'Buku tidak ditemukan'], 404);
        }

        $bookData = json_decode($text, true);

        if (!$bookData) {
            return response()->json(['error' => 'Format data tidak valid'], 500);
        }

        return response()->json([
            'title'          => $bookData['title'] ?? '',
            'author'         => $bookData['author'] ?? '',
            'publisher'      => $bookData['publisher'] ?? '',
            'published_year' => $bookData['published_year'] ?? '',
            'description'    => $bookData['description'] ?? '',
            'cover_image'    => '',
        ]);
    }
}