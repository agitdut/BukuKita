<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'slug' => 'fiksi'],
            ['name' => 'Non-Fiksi', 'slug' => 'non-fiksi'],
            ['name' => 'Sains & Teknologi', 'slug' => 'sains-teknologi'],
            ['name' => 'Sejarah', 'slug' => 'sejarah'],
            ['name' => 'Filsafat', 'slug' => 'filsafat'],
            ['name' => 'Agama', 'slug' => 'agama'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan'],
            ['name' => 'Ekonomi & Bisnis', 'slug' => 'ekonomi-bisnis'],
            ['name' => 'Novel', 'slug' => 'novel'],
            ['name' => 'Komik', 'slug' => 'komik'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
