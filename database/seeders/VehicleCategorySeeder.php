<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Mobil Sedan',
                'slug' => 'mobil-sedan',
                'description' => 'Kendaraan sedan untuk perjalanan nyaman',
                'is_active' => true,
            ],
            [
                'name' => 'Mobil SUV',
                'slug' => 'mobil-suv',
                'description' => 'Kendaraan SUV untuk keluarga dan adventure',
                'is_active' => true,
            ],
            [
                'name' => 'Bus Medium',
                'slug' => 'bus-medium',
                'description' => 'Bus kapasitas medium untuk wisata grup',
                'is_active' => true,
            ],
            [
                'name' => 'Bus Besar',
                'slug' => 'bus-besar',
                'description' => 'Bus kapasitas besar untuk rombongan',
                'is_active' => true,
            ],
            [
                'name' => 'Mobil MPV',
                'slug' => 'mobil-mpv',
                'description' => 'Multi Purpose Vehicle untuk keluarga',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\VehicleCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
