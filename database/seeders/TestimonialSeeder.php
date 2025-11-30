<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        Testimonial::create([
            'name' => 'Ahmad Rizki',
            'location' => 'Jakarta',
            'rating' => 5,
            'message' => 'Pelayanan sangat memuaskan! Kendaraan bersih dan terawat. Pasti akan rental lagi di sini.',
            'is_active' => true,
            'is_featured' => true,
            'display_order' => 1,
        ]);
        Testimonial::create([
            'name' => 'Sari Dewi',
            'location' => 'Bandung',
            'rating' => 5,
            'message' => 'Proses booking mudah dan cepat. Harga juga sangat terjangkau. Recommended!',
            'is_active' => true,
            'is_featured' => true,
            'display_order' => 2,
        ]);
        Testimonial::create([
            'name' => 'Budi Santoso',
            'location' => 'Surabaya',
            'rating' => 5,
            'message' => 'Driver sangat profesional dan ramah. Perjalanan jadi sangat nyaman dan aman.',
            'is_active' => true,
            'is_featured' => false,
            'display_order' => 3,
        ]);
    }
}
