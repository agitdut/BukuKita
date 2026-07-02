<?php

use App\Models\Book;
use App\Models\User;
use Database\Seeders\RoleAndUserSeeder;

beforeEach(function () {
    $this->seed(RoleAndUserSeeder::class);
});

it('menampilkan daftar buku untuk user yang login', function () {
    $user = User::where('email', 'member@gmail.com')->first();
    Book::factory()->create(['title' => 'Buku Test']);

    $response = $this->actingAs($user)->get(route('books.index'));

    $response->assertStatus(200);
    $response->assertSee('Buku Test');
});

it('tidak mengizinkan member membuat buku', function () {
    $user = User::where('email', 'member@gmail.com')->first();

    $response = $this->actingAs($user)->get(route('books.create'));

    $response->assertStatus(403);
});

it('mengizinkan admin membuat buku', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();

    $response = $this->actingAs($admin)->get(route('books.create'));

    $response->assertStatus(200);
});

it('mengizinkan staff membuat buku', function () {
    $staff = User::where('email', 'staff@gmail.com')->first();

    $response = $this->actingAs($staff)->get(route('books.create'));

    $response->assertStatus(200);
});

it('hanya admin yang bisa menghapus buku', function () {
    $book = Book::factory()->create();
    $staff = User::where('email', 'staff@gmail.com')->first();

    $response = $this->actingAs($staff)->delete(route('books.destroy', $book));

    $response->assertStatus(403);
});

it('admin bisa menghapus buku', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();
    $book = Book::factory()->create();

    $response = $this->actingAs($admin)->delete(route('books.destroy', $book));

    $response->assertStatus(302);
    expect(Book::find($book->id))->toBeNull();
});
