<?php

namespace Database\Seeders;

use App\Models\RentalCategory;
use Illuminate\Database\Seeder;

class RentalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Per Jam',
                'slug' => 'per-jam',
                'description' => 'Sewa kendaraan per jam',
                'satuan' => 'jam',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Setengah Hari',
                'slug' => 'setengah-hari',
                'description' => 'Sewa kendaraan setengah hari',
                'satuan' => 'setengah hari',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Per Hari',
                'slug' => 'per-hari',
                'description' => 'Sewa kendaraan per hari',
                'satuan' => 'hari',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Per Bulan',
                'slug' => 'per-bulan',
                'description' => 'Sewa kendaraan per bulan',
                'satuan' => 'bulan',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            RentalCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
