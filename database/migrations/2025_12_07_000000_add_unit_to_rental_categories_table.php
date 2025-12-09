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
        if (!Schema::hasTable('rental_categories')) {
            return;
        }
        
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        
        if ($driver === 'sqlite') {
            // For SQLite, use PRAGMA to check columns
            $columns = DB::select("PRAGMA table_info(rental_categories)");
            $columnNames = array_column($columns, 'name');
            
            if (!in_array('satuan', $columnNames)) {
                DB::statement('ALTER TABLE rental_categories ADD COLUMN satuan VARCHAR(255) NULL');
            }
        } else {
            // For MySQL, PostgreSQL, etc.
            if (!Schema::hasColumn('rental_categories', 'satuan')) {
                Schema::table('rental_categories', function (Blueprint $table) {
                    $table->string('satuan')->nullable()->after('description');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support DROP COLUMN easily, skip for now
            // You would need to recreate the table
        } else {
            if (Schema::hasColumn('rental_categories', 'satuan')) {
                Schema::table('rental_categories', function (Blueprint $table) {
                    $table->dropColumn('satuan');
                });
            }
        }
    }
};
