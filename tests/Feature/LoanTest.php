<?php

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Database\Seeders\RoleAndUserSeeder;
use Carbon\Carbon;

beforeEach(function () {
    $this->seed(RoleAndUserSeeder::class);
});

it('menampilkan daftar peminjaman untuk admin', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();

    $response = $this->actingAs($admin)->get(route('loans.index'));

    $response->assertStatus(200);
});

it('tidak mengizinkan member melihat peminjaman', function () {
    $user = User::where('email', 'member@gmail.com')->first();

    $response = $this->actingAs($user)->get(route('loans.index'));

    $response->assertStatus(403);
});

it('bisa membuat peminjaman baru', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();
    $member = User::where('email', 'member@gmail.com')->first();
    $book = Book::factory()->create(['stock' => 5]);

    $response = $this->actingAs($admin)->post(route('loans.store'), [
        'user_id'   => $member->id,
        'book_id'   => $book->id,
        'loan_date' => Carbon::now()->format('Y-m-d'),
        'due_date'  => Carbon::now()->addDays(7)->format('Y-m-d'),
    ]);

    $response->assertStatus(302);
    expect(Loan::count())->toBe(1);
    expect($book->fresh()->stock)->toBe(4);
});

it('gagal membuat peminjaman jika stok habis', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();
    $member = User::where('email', 'member@gmail.com')->first();
    $book = Book::factory()->create(['stock' => 0]);

    $response = $this->actingAs($admin)->post(route('loans.store'), [
        'user_id'   => $member->id,
        'book_id'   => $book->id,
        'loan_date' => Carbon::now()->format('Y-m-d'),
        'due_date'  => Carbon::now()->addDays(7)->format('Y-m-d'),
    ]);

    $response->assertSessionHas('error');
    expect(Loan::count())->toBe(0);
});

it('bisa memproses pengembalian dengan denda', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();
    $member = User::where('email', 'member@gmail.com')->first();
    $book = Book::factory()->create(['stock' => 3]);

    $loan = Loan::create([
        'user_id'   => $member->id,
        'book_id'   => $book->id,
        'loan_date' => Carbon::now()->subDays(10),
        'due_date'  => Carbon::now()->subDays(3),
        'status'    => 'borrowed',
        'fine'      => 0,
    ]);

    $response = $this->actingAs($admin)->post(route('loans.return', $loan));

    $response->assertStatus(302);
    $loan->refresh();
    expect($loan->status)->toBe('returned');
    expect($loan->fine)->toBeGreaterThan(0);
    expect($book->fresh()->stock)->toBe(4);
});
