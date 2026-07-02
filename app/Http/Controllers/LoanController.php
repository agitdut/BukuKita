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
    public function index(Request $request)
    {
        Loan::where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now())
            ->update(['status' => 'overdue']);

        $search = $request->input('search');

        $loans = Loan::with(['user', 'book'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    })->orWhereHas('book', function ($b) use ($search) {
                        $b->where('title', 'like', "%{$search}%")
                          ->orWhere('isbn', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('loans.index', compact('loans', 'search'));
    }

    // Form tambah peminjaman
    public function create()
    {
        $books = Book::where('stock', '>', 0)->get();
        $users = User::all();
        return view('loans.create', compact('books', 'users'));
    }

    // Simpan peminjaman baru
    // Simpan peminjaman baru
public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'book_id' => 'required|exists:books,id',
        'loan_date' => 'required|date',
        'due_date'  => 'required|date|after:loan_date',
    ]);

    $book = Book::findOrFail($request->book_id);

    // Validasi stok sebelum proses peminjaman
    if ($book->stock <= 0) {
        return redirect()->route('loans.create')
            ->with('error', 'Stok buku "' . $book->title . '" sudah habis, tidak bisa dipinjam!');
    }

    // Kurangi stok buku
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
            $daysLate = (int) ceil((float) $returnDate->diffInDays($loan->due_date, true));
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