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
        if (DB::getDriverName() === 'sqlite') {
            // Untuk SQLite, kita perlu menggunakan raw SQL
            DB::statement('ALTER TABLE users ADD COLUMN nomor_whatsapp VARCHAR(20) NULL');
            DB::statement('ALTER TABLE users ADD COLUMN foto_profil VARCHAR(255) NULL');
            DB::statement('ALTER TABLE users ADD COLUMN data_anggota TEXT NULL');
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nomor_whatsapp', 20)->nullable()->after('email');
                $table->string('foto_profil')->nullable()->after('nomor_whatsapp');
                $table->json('data_anggota')->nullable()->after('foto_profil');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite tidak mendukung DROP COLUMN dengan baik
            // Kita perlu membuat tabel baru tanpa kolom tersebut
            // Tapi untuk keamanan, kita skip rollback untuk SQLite
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['nomor_whatsapp', 'foto_profil', 'data_anggota']);
            });
        }
    }
};
