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

        // Books - Admin & Staff full akses, Member hanya lihat
    Route::get('/books/fetch-isbn', [BookController::class, 'fetchByIsbn'])->name('books.fetch-isbn');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create')->middleware('role:admin|staff');
    Route::post('/books', [BookController::class, 'store'])->name('books.store')->middleware('role:admin|staff');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit')->middleware('role:admin|staff');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update')->middleware('role:admin|staff');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy')->middleware('role:admin');

    // Loans - Hanya Admin & Staff
    Route::middleware('role:admin|staff')->group(function () {
        Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
        Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    });

    // Users - Hanya Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Chat AI - Semua role bisa akses
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/clear', [ChatController::class, 'clearHistory'])->name('chat.clear');
});

require __DIR__.'/auth.php';