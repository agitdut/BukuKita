<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}