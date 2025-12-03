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
        // Update existing NULL values to 0
        DB::statement('UPDATE vehicles SET price_per_day = 0 WHERE price_per_day IS NULL');
        
        // Change column to have default 0
        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('price_per_day', 10, 2)->default(0)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('price_per_day', 10, 2)->nullable()->change();
        });
    }
};
