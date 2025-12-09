<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update satuan based on category names
        DB::table('rental_categories')
            ->where('name', 'Per Jam')
            ->update(['satuan' => 'jam']);
            
        DB::table('rental_categories')
            ->where('name', 'Setengah Hari')
            ->update(['satuan' => 'setengah hari']);
            
        DB::table('rental_categories')
            ->where('name', 'Per Hari')
            ->update(['satuan' => 'hari']);
            
        DB::table('rental_categories')
            ->where('name', 'Per Bulan')
            ->update(['satuan' => 'bulan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};

