<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update path dari 'vehicles/...' ke 'images/vehicles/...'
        $images = \Illuminate\Support\Facades\DB::table('vehicle_images')
            ->where('image_path', 'like', 'vehicles/%')
            ->get();
        
        foreach ($images as $image) {
            $newPath = str_replace('vehicles/', 'images/vehicles/', $image->image_path);
            \Illuminate\Support\Facades\DB::table('vehicle_images')
                ->where('id', $image->id)
                ->update(['image_path' => $newPath]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert path dari 'images/vehicles/...' ke 'vehicles/...'
        $images = \Illuminate\Support\Facades\DB::table('vehicle_images')
            ->where('image_path', 'like', 'images/vehicles/%')
            ->get();
        
        foreach ($images as $image) {
            $oldPath = str_replace('images/vehicles/', 'vehicles/', $image->image_path);
            \Illuminate\Support\Facades\DB::table('vehicle_images')
                ->where('id', $image->id)
                ->update(['image_path' => $oldPath]);
        }
    }
};
