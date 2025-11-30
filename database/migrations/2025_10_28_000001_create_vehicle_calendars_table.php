<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('blocked_by', ['booking', 'admin']);
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->unique(['vehicle_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_calendars');
    }
};
