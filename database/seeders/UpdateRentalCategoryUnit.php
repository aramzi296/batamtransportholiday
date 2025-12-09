<?php

namespace Database\Seeders;

use App\Models\RentalCategory;
use Illuminate\Database\Seeder;

class UpdateRentalCategoryUnit extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update unit based on category name
        $unitMapping = [
            'Per Jam' => 'jam',
            'Setengah Hari' => 'setengah hari',
            'Per Hari' => 'hari',
            'Per Bulan' => 'bulan',
        ];

        foreach ($unitMapping as $name => $unit) {
            RentalCategory::where('name', $name)->update(['unit' => $unit]);
        }

        $this->command->info('Rental category units updated successfully!');
    }
}

