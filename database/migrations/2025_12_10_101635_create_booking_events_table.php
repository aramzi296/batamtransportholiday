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
        Schema::create('booking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('event_type'); // customer_submit, email_sent_customer, email_sent_admin, whatsapp_sent_admin, status_changed, email_confirmation_sent, whatsapp_confirmation_sent
            $table->string('description')->nullable();
            $table->text('metadata')->nullable(); // JSON data untuk menyimpan detail tambahan
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang melakukan action (jika manual)
            $table->timestamps();
            
            $table->index('booking_id');
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_events');
    }
};
