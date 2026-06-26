<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Books - Admin & Staff
    Route::get('/books/fetch-isbn', [BookController::class, 'fetchByIsbn'])->name('books.fetch-isbn');
    Route::resource('books', BookController::class);

    // Loans - Admin & Staff
    Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');

    // Users - Hanya Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

});

require __DIR__.'/auth.php';