<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleBrand;
use Illuminate\Support\Str;

class VehicleBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Mobil
            'Toyota',
            'Honda',
            'Suzuki',
            'Daihatsu',
            'Mitsubishi',
            'Nissan',
            'Mazda',
            'Hyundai',
            'Kia',
            'Wuling',
            'Chery',
            'DFSK',
            'Isuzu',
            'Mercedes-Benz',
            'BMW',
            'Audi',
            'Volkswagen',
            'Peugeot',
            'Renault',
            'Ford',
            'Chevrolet',
            'Lexus',
            'Mini',
            'Volvo',
            'Subaru',
            'Jeep',
            'Fiat',
            'Datsun',
            'Proton',
            'Geely',
            'MG',
            'Tesla',
            
            // Motor
            'Yamaha',
            'Kawasaki',
            'Bajaj',
            'Vespa',
            'Piaggio',
            'Harley-Davidson',
            'Ducati',
            'KTM',
            'Husqvarna',
            'Royal Enfield',
        ];

        foreach ($brands as $brandName) {
            VehicleBrand::firstOrCreate(
                ['name' => $brandName],
                [
                    'slug' => Str::slug($brandName),
                    'is_active' => true,
                ]
            );
        }
    }
}
