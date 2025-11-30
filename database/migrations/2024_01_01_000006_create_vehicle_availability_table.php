<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicle_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->date('date');
            $table->boolean('is_available')->default(true);
            $table->text('reason')->nullable(); // reason for unavailability
            $table->timestamps();
            
            $table->unique(['vehicle_id', 'date']); // prevent duplicate entries for same vehicle and date
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_availability');
    }
};