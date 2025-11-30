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
        // Untuk SQLite, kita perlu mengubah CHECK constraint pada kolom role
        // Karena SQLite tidak mendukung ALTER COLUMN dengan baik, kita perlu:
        // 1. Buat tabel baru dengan constraint yang benar
        // 2. Copy data
        // 3. Drop tabel lama
        // 4. Rename tabel baru
        
        if (DB::getDriverName() === 'sqlite') {
            // Backup data
            $users = DB::table('users')->get();
            
            // Drop dan recreate tabel dengan constraint baru
            DB::statement('PRAGMA foreign_keys=off');
            
            // Buat tabel baru
            DB::statement("
                CREATE TABLE users_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    email_verified_at DATETIME NULL,
                    password VARCHAR(255) NOT NULL,
                    role VARCHAR(255) NOT NULL DEFAULT 'customer' CHECK(role IN ('admin', 'customer', 'member')),
                    remember_token VARCHAR(100) NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                )
            ");
            
            // Copy data
            foreach ($users as $user) {
                DB::table('users_new')->insert([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'password' => $user->password,
                    'role' => $user->role,
                    'remember_token' => $user->remember_token,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }
            
            // Drop tabel lama
            DB::statement('DROP TABLE users');
            
            // Rename tabel baru
            DB::statement('ALTER TABLE users_new RENAME TO users');
            
            // Recreate indexes
            DB::statement('CREATE UNIQUE INDEX users_email_unique ON users(email)');
            
            DB::statement('PRAGMA foreign_keys=on');
        } else {
            // Untuk MySQL/MariaDB, gunakan MODIFY COLUMN
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'member') DEFAULT 'customer'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Backup data
            $users = DB::table('users')->get();
            
            DB::statement('PRAGMA foreign_keys=off');
            
            // Buat tabel dengan constraint lama
            DB::statement("
                CREATE TABLE users_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    email_verified_at DATETIME NULL,
                    password VARCHAR(255) NOT NULL,
                    role VARCHAR(255) NOT NULL DEFAULT 'customer' CHECK(role IN ('admin', 'customer')),
                    remember_token VARCHAR(100) NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                )
            ");
            
            // Copy data (hanya yang role-nya bukan 'member')
            foreach ($users as $user) {
                if (in_array($user->role, ['admin', 'customer'])) {
                    DB::table('users_new')->insert([
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'password' => $user->password,
                        'role' => $user->role,
                        'remember_token' => $user->remember_token,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);
                }
            }
            
            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_new RENAME TO users');
            DB::statement('CREATE UNIQUE INDEX users_email_unique ON users(email)');
            
            DB::statement('PRAGMA foreign_keys=on');
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer') DEFAULT 'customer'");
        }
    }
};
