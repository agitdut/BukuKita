<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    // Daftar semua peminjaman
    public function index()
    {
        $loans = Loan::with(['user', 'book'])->latest()->paginate(10);
        return view('loans.index', compact('loans'));
    }

    // Form tambah peminjaman
    public function create()
    {
        $books = Book::where('stock', '>', 0)->get();
        $users = User::all();
        return view('loans.create', compact('books', 'users'));
    }

    // Simpan peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date'  => 'required|date|after:loan_date',
        ]);

        // Kurangi stok buku
        $book = Book::findOrFail($request->book_id);
        $book->decrement('stock');

        Loan::create([
            'user_id'   => $request->user_id,
            'book_id'   => $request->book_id,
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'status'    => 'borrowed',
            'fine'      => 0,
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dicatat!');
    }

    // Proses pengembalian buku
    public function return(Loan $loan)
    {
        $returnDate = Carbon::now();
        $fine = 0;

        // Hitung denda jika terlambat (Rp 1.000 per hari)
        if ($returnDate->gt($loan->due_date)) {
            $daysLate = $returnDate->diffInDays($loan->due_date);
            $fine = $daysLate * 1000;
        }

        $loan->update([
            'return_date' => $returnDate,
            'status'      => 'returned',
            'fine'        => $fine,
        ]);

        // Tambah stok buku kembali
        $loan->book->increment('stock');

        return redirect()->route('loans.index')
            ->with('success', 'Buku berhasil dikembalikan!' . ($fine > 0 ? ' Denda: Rp ' . number_format($fine, 0, ',', '.') : ''));
    }

    // Detail peminjaman
    public function show(Loan $loan)
    {
        return view('loans.show', compact('loan'));
    }
}