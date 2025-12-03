<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VehicleCategory;
use App\Models\ArticleCategory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@carrental.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create Sample Customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        // Create Vehicle Categories
        VehicleCategory::create([
            'name' => 'Mobil Sedan',
            'slug' => 'mobil-sedan',
            'description' => 'Kendaraan sedan dengan kenyamanan tinggi'
        ]);

        VehicleCategory::create([
            'name' => 'SUV',
            'slug' => 'suv',
            'description' => 'Sport Utility Vehicle untuk perjalanan keluarga'
        ]);

        VehicleCategory::create([
            'name' => 'Bus',
            'slug' => 'bus',
            'description' => 'Bus untuk perjalanan grup atau rombongan'
        ]);

        VehicleCategory::create([
            'name' => 'Mini Bus',
            'slug' => 'mini-bus',
            'description' => 'Mini bus untuk grup kecil'
        ]);

        // Create Article Categories
        ArticleCategory::create([
            'name' => 'Umum',
            'slug' => 'umum',
            'description' => 'Artikel kategori umum'
        ]);

        ArticleCategory::create([
            'name' => 'Tips Perjalanan',
            'slug' => 'tips-perjalanan',
            'description' => 'Tips dan trik untuk perjalanan yang nyaman'
        ]);

        ArticleCategory::create([
            'name' => 'Perawatan Kendaraan',
            'slug' => 'perawatan-kendaraan',
            'description' => 'Informasi perawatan kendaraan'
        ]);

        // Seed vehicle brands
        $this->call(VehicleBrandSeeder::class);
        // Seed vehicles
        $this->call(VehicleSeeder::class);
        // Seed testimonials
        $this->call(TestimonialSeeder::class);
    }
}
