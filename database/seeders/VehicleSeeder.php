<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'name' => 'Toyota Avanza G',
                'slug' => 'toyota-avanza-g',
                'category_id' => 2, // SUV
                'description' => 'MPV keluarga yang nyaman dengan kapasitas 7 penumpang. Cocok untuk perjalanan keluarga atau rombongan kecil.',
                'price_per_day' => 300000,
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'year' => 2022,
                'color' => 'Silver',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 7,
                'plate_number' => 'B 1234 ABC',
                'features' => ['AC', 'Audio System', 'Central Lock', 'Electric Mirror'],
                'images' => [
                    'https://images.unsplash.com/photo-1562141961-e5ac5e0d2024?ixlib=rb-4.0.3&w=800',
                    'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&w=800'
                ],
                'is_available' => true,
            ],
            [
                'name' => 'Honda Civic Sedan',
                'slug' => 'honda-civic-sedan',
                'category_id' => 1, // Sedan
                'description' => 'Sedan premium dengan performa tinggi dan kenyamanan maksimal. Ideal untuk perjalanan bisnis atau pribadi.',
                'price_per_day' => 450000,
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2023,
                'color' => 'Black',
                'fuel_type' => 'Bensin',
                'transmission' => 'Automatic',
                'seats' => 5,
                'plate_number' => 'B 5678 DEF',
                'features' => ['AC', 'Leather Seats', 'Sunroof', 'GPS Navigation', 'Bluetooth'],
                'images' => [
                    'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?ixlib=rb-4.0.3&w=800',
                    'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?ixlib=rb-4.0.3&w=800'
                ],
                'is_available' => true,
            ],
            [
                'name' => 'Suzuki Ertiga GL',
                'slug' => 'suzuki-ertiga-gl',
                'category_id' => 2, // SUV
                'description' => 'MPV kompak dengan efisiensi bahan bakar yang baik. Sangat cocok untuk perjalanan dalam kota maupun luar kota.',
                'price_per_day' => 280000,
                'brand' => 'Suzuki',
                'model' => 'Ertiga',
                'year' => 2021,
                'color' => 'White',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 7,
                'plate_number' => 'B 9101 GHI',
                'features' => ['AC', 'Audio System', 'Central Lock', 'Electric Window'],
                'images' => [
                    'https://images.unsplash.com/photo-1600712242805-5f78671b24da?ixlib=rb-4.0.3&w=800'
                ],
                'is_available' => true,
            ],
            [
                'name' => 'Isuzu Elf Bus',
                'slug' => 'isuzu-elf-bus',
                'category_id' => 4, // Mini Bus
                'description' => 'Mini bus dengan kapasitas 16 penumpang. Ideal untuk perjalanan grup, wisata, atau acara perusahaan.',
                'price_per_day' => 800000,
                'brand' => 'Isuzu',
                'model' => 'Elf',
                'year' => 2020,
                'color' => 'Blue',
                'fuel_type' => 'Solar',
                'transmission' => 'Manual',
                'seats' => 16,
                'plate_number' => 'B 1122 JKL',
                'features' => ['AC', 'Audio System', 'Comfortable Seats', 'Luggage Space'],
                'images' => [
                    'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&w=800'
                ],
                'is_available' => true,
            ],
            [
                'name' => 'Mercedes Big Bus',
                'slug' => 'mercedes-big-bus',
                'category_id' => 3, // Bus
                'description' => 'Bus besar premium dengan kapasitas 40 penumpang. Dilengkapi fasilitas lengkap untuk perjalanan jarak jauh yang nyaman.',
                'price_per_day' => 1500000,
                'brand' => 'Mercedes',
                'model' => 'OH 1626',
                'year' => 2022,
                'color' => 'White',
                'fuel_type' => 'Solar',
                'transmission' => 'Manual',
                'seats' => 40,
                'plate_number' => 'B 3344 MNO',
                'features' => ['AC', 'Reclining Seats', 'TV/DVD', 'WiFi', 'Toilet', 'Luggage Space'],
                'images' => [
                    'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&w=800'
                ],
                'is_available' => true,
            ],
            [
                'name' => 'Toyota Fortuner VRZ',
                'slug' => 'toyota-fortuner-vrz',
                'category_id' => 2, // SUV
                'description' => 'SUV premium dengan performa tangguh dan kenyamanan tinggi. Cocok untuk perjalanan off-road maupun dalam kota.',
                'price_per_day' => 650000,
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'year' => 2023,
                'color' => 'Dark Grey',
                'fuel_type' => 'Solar',
                'transmission' => 'Automatic',
                'seats' => 7,
                'plate_number' => 'B 5566 PQR',
                'features' => ['AC', 'Leather Seats', '4WD', 'GPS Navigation', 'Parking Sensor'],
                'images' => [
                    'https://images.unsplash.com/photo-1594736797933-d0d1dc73ce64?ixlib=rb-4.0.3&w=800'
                ],
                'is_available' => true,
            ]
        ];

        foreach ($vehicles as $vehicle) {
            \App\Models\Vehicle::create($vehicle);
        }
    }
}
