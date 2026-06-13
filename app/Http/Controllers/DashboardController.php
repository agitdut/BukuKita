<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks  = Book::count();
        $totalLoans  = Loan::count();
        $activeLoans = Loan::where('status', 'borrowed')->count();
        $totalUsers  = User::count();

        $recentLoans = Loan::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $recentBooks = Book::latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBooks',
            'totalLoans',
            'activeLoans',
            'totalUsers',
            'recentLoans',
            'recentBooks'
        ));
    }
}