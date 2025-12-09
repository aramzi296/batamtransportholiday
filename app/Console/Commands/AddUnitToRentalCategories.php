<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUnitToRentalCategories extends Command
{
    protected $signature = 'rental-categories:add-unit';
    protected $description = 'Add unit column to rental_categories table and update existing data';

    public function handle()
    {
        $this->info('Checking rental_categories table...');
        
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            $this->info("Database driver: {$driver}");
            
            if ($driver === 'sqlite') {
                // For SQLite
                $columns = DB::select("PRAGMA table_info(rental_categories)");
                $columnNames = array_column($columns, 'name');
                
                $this->info("Existing columns: " . implode(', ', $columnNames));
                
                if (!in_array('satuan', $columnNames)) {
                    $this->info("Adding 'satuan' column...");
                    DB::statement('ALTER TABLE rental_categories ADD COLUMN satuan VARCHAR(255) NULL');
                    $this->info("Column 'satuan' added successfully!");
                } else {
                    $this->info("Column 'satuan' already exists.");
                }
            } else {
                // For MySQL, PostgreSQL, etc.
                if (!Schema::hasColumn('rental_categories', 'satuan')) {
                    $this->info("Adding 'satuan' column...");
                    Schema::table('rental_categories', function ($table) {
                        $table->string('satuan')->nullable()->after('description');
                    });
                    $this->info("Column 'satuan' added successfully!");
                } else {
                    $this->info("Column 'satuan' already exists.");
                }
            }
            
            // Update existing data
            $this->info("\nUpdating existing data...");
            $updates = [
                'Per Jam' => 'jam',
                'Setengah Hari' => 'setengah hari',
                'Per Hari' => 'hari',
                'Per Bulan' => 'bulan',
            ];
            
            foreach ($updates as $name => $satuan) {
                $updated = DB::table('rental_categories')
                    ->where('name', $name)
                    ->update(['satuan' => $satuan]);
                $this->info("Updated '{$name}' => '{$satuan}' ({$updated} rows)");
            }
            
            // Show all categories
            $this->info("\nCurrent rental categories:");
            $categories = DB::table('rental_categories')->get();
            foreach ($categories as $cat) {
                $this->line("  - {$cat->name} => " . ($cat->satuan ?? 'NULL'));
            }
            
            $this->info("\nDone!");
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

