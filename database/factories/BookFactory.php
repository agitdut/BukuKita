<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'isbn'           => $this->faker->unique()->isbn13(),
            'title'          => $this->faker->sentence(3),
            'author'         => $this->faker->name(),
            'publisher'      => $this->faker->company(),
            'published_year' => $this->faker->year(),
            'stock'          => $this->faker->numberBetween(1, 10),
            'description'    => $this->faker->paragraph(),
        ];
    }
}
