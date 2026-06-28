<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks  = Book::count();
        $recentBooks = Book::latest()->take(5)->get();

        $data = compact('totalBooks', 'recentBooks');

        if (auth()->user()->hasAnyRole(['admin', 'staff'])) {
            $data['totalLoans']  = Loan::count();
            $data['activeLoans'] = Loan::where('status', 'borrowed')->count();
            $data['recentLoans'] = Loan::with(['user', 'book'])->latest()->take(5)->get();

             // Ambil buku yang sudah melewati jatuh tempo dan belum dikembalikan
            $data['overdueLoans'] = Loan::with(['user', 'book'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->where('due_date', '<', Carbon::today())
            ->whereNull('return_date')
            ->orderBy('due_date', 'asc')
            ->get();
        }

        if (auth()->user()->hasRole('admin')) {
            $data['totalUsers'] = User::count();
        }

        if (auth()->user()->hasRole('member')) {
            $data['myLoans'] = Loan::with('book')
                ->where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', $data);
    }
}