<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('vehicle_categories')->onDelete('cascade');
            $table->text('description');
            $table->decimal('price_per_day', 10, 2);
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('color');
            $table->string('fuel_type');
            $table->string('transmission');
            $table->integer('seats');
            $table->string('plate_number')->unique();
            $table->json('features')->nullable(); // AC, GPS, etc
            $table->json('images')->nullable(); // array of image paths
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};