<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Tambah user anggota
        $memberRole = Role::findByName('member');

        $members = [
            ['name' => 'Budi Santoso',   'email' => 'budi@example.com'],
            ['name' => 'Siti Rahayu',    'email' => 'siti@example.com'],
            ['name' => 'Ahmad Fauzi',    'email' => 'ahmad@example.com'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@example.com'],
            ['name' => 'Rizky Pratama',  'email' => 'rizky@example.com'],
        ];

        foreach ($members as $member) {
            $user = User::firstOrCreate(
                ['email' => $member['email']],
                ['name' => $member['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole($memberRole);
        }

        // Tambah buku dummy
        $books = [
            [
                'isbn'           => '9780743273565',
                'title'          => 'The Great Gatsby',
                'author'         => 'F. Scott Fitzgerald',
                'publisher'      => 'Scribner',
                'published_year' => '1925',
                'stock'          => 3,
                'description'    => 'Novel klasik Amerika tentang kemewahan dan kejatuhan.',
            ],
            [
                'isbn'           => '9780061096525',
                'title'          => 'To Kill a Mockingbird',
                'author'         => 'Harper Lee',
                'publisher'      => 'HarperCollins',
                'published_year' => '1960',
                'stock'          => 2,
                'description'    => 'Novel tentang rasisme dan keadilan di Amerika Selatan.',
            ],
            [
                'isbn'           => '9780451524935',
                'title'          => '1984',
                'author'         => 'George Orwell',
                'publisher'      => 'Signet Classic',
                'published_year' => '1949',
                'stock'          => 4,
                'description'    => 'Dystopia klasik tentang totalitarisme.',
            ],
            [
                'isbn'           => '9780316769174',
                'title'          => 'The Catcher in the Rye',
                'author'         => 'J.D. Salinger',
                'publisher'      => 'Little Brown',
                'published_year' => '1951',
                'stock'          => 2,
                'description'    => 'Novel tentang pemberontakan remaja.',
            ],
            [
                'isbn'           => '9780547928227',
                'title'          => 'The Hobbit',
                'author'         => 'J.R.R. Tolkien',
                'publisher'      => 'Houghton Mifflin',
                'published_year' => '1937',
                'stock'          => 5,
                'description'    => 'Petualangan Bilbo Baggins di dunia Middle Earth.',
            ],
            [
                'isbn'           => '9780439708180',
                'title'          => 'Harry Potter and the Sorcerer Stone',
                'author'         => 'J.K. Rowling',
                'publisher'      => 'Scholastic',
                'published_year' => '1997',
                'stock'          => 6,
                'description'    => 'Petualangan Harry Potter di Hogwarts.',
            ],
        ];

        foreach ($books as $book) {
            Book::firstOrCreate(['isbn' => $book['isbn']], $book);
        }

        // Tambah data peminjaman dummy
        $users = User::role('member')->get();
        $allBooks = Book::all();

        $loanData = [
            [
                'user_id'     => $users[0]->id,
                'book_id'     => $allBooks[0]->id,
                'loan_date'   => Carbon::now()->subDays(10),
                'due_date'    => Carbon::now()->subDays(3),
                'return_date' => Carbon::now()->subDays(1),
                'status'      => 'returned',
                'fine'        => 2000,
            ],
            [
                'user_id'   => $users[1]->id,
                'book_id'   => $allBooks[1]->id,
                'loan_date' => Carbon::now()->subDays(5),
                'due_date'  => Carbon::now()->addDays(2),
                'status'    => 'borrowed',
                'fine'      => 0,
            ],
            [
                'user_id'   => $users[2]->id,
                'book_id'   => $allBooks[2]->id,
                'loan_date' => Carbon::now()->subDays(15),
                'due_date'  => Carbon::now()->subDays(8),
                'status'    => 'overdue',
                'fine'      => 8000,
            ],
            [
                'user_id'     => $users[3]->id,
                'book_id'     => $allBooks[3]->id,
                'loan_date'   => Carbon::now()->subDays(7),
                'due_date'    => Carbon::now()->subDays(1),
                'return_date' => Carbon::now(),
                'status'      => 'returned',
                'fine'        => 0,
            ],
            [
                'user_id'   => $users[4]->id,
                'book_id'   => $allBooks[4]->id,
                'loan_date' => Carbon::now()->subDays(2),
                'due_date'  => Carbon::now()->addDays(5),
                'status'    => 'borrowed',
                'fine'      => 0,
            ],
        ];

        foreach ($loanData as $loan) {
            Loan::create($loan);
        }
    }
}