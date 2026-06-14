<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        $messages = ChatMessage::where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:5000',
        ]);

        $prompt = $request->input('prompt');
        $apiKey = env('GROQ_API_KEY');

        // Ambil daftar buku dari database untuk konteks AI
        $books = Book::select('title', 'author', 'publisher', 'stock')->get();
        $bookList = $books->map(function($book) {
            return "- {$book->title} karya {$book->author} (Stok: {$book->stock})";
        })->implode("\n");

        $systemPrompt = "Kamu adalah asisten perpustakaan digital BukuKita yang membantu pengguna mencari informasi buku dan menjawab pertanyaan seputar perpustakaan. 
        
Berikut adalah daftar buku yang tersedia di perpustakaan kami:
{$bookList}

Jawab pertanyaan pengguna dengan ramah dan informatif. Jika ditanya tentang buku yang tidak ada di daftar, sampaikan bahwa buku tersebut tidak tersedia. Gunakan Bahasa Indonesia.";

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
                    'content' => $systemPrompt,
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal menghubungi AI'], 500);
        }

        $data   = $response->json();
        $aiText = $data['choices'][0]['message']['content'] ?? null;

        if (!$aiText) {
            return response()->json(['error' => 'Respons AI kosong'], 500);
        }

        ChatMessage::create([
            'user_id'  => auth()->id(),
            'prompt'   => $prompt,
            'response' => $aiText,
        ]);

        return response()->json(['text' => $aiText]);
    }

    public function clearHistory()
    {
        ChatMessage::where('user_id', auth()->id())->delete();
        return redirect()->route('chat.index')
            ->with('success', 'Riwayat chat berhasil dihapus!');
    }
}