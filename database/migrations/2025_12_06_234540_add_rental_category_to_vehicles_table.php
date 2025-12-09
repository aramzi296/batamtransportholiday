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
        Schema::create('vehicle_rental_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('rental_category_id')->constrained('rental_categories')->onDelete('cascade');
            $table->boolean('with_driver')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['vehicle_id', 'rental_category_id', 'with_driver'], 'vehicle_rental_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_rental_categories');
    }
};
