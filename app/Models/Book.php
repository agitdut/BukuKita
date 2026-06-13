<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Book extends Model
{
    protected $fillable = [
        'isbn',
        'title',
        'author',
        'publisher',
        'published_year',
        'cover_image',
        'description',
        'stock',
    ];

    // Relasi: satu buku bisa dipinjam banyak kali
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}