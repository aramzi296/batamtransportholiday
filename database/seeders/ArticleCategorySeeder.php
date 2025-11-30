<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;
use Illuminate\Support\Str;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Umum',
                'slug' => 'umum',
                'description' => 'Kategori artikel umum dan informasi dasar',
                'is_active' => true,
            ],
            [
                'name' => 'Tips & Trik',
                'slug' => 'tips-trik',
                'description' => 'Tips dan trik seputar rental kendaraan',
                'is_active' => true,
            ],
            [
                'name' => 'Berita',
                'slug' => 'berita',
                'description' => 'Berita terkini seputar industri rental',
                'is_active' => true,
            ],
            [
                'name' => 'Panduan',
                'slug' => 'panduan',
                'description' => 'Panduan lengkap untuk customer',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ArticleCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
