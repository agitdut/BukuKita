<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'fine',
    ];

    // Casting agar tanggal otomatis jadi Carbon object
    protected $casts = [
        'loan_date'   => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Hitung denda saat ini (untuk pinjaman aktif yang telat)
    public function currentFine()
    {
        if ($this->status === 'returned') {
            return $this->fine;
        }

        if ($this->due_date->isPast()) {
            $daysLate = (int) ceil($this->due_date->diffInDays(now(), true));
            return $daysLate * 1000;
        }

        return 0;
    }
}