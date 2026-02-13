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
        // First, update any registry_clerk or department_head to 'user'
        DB::table('employees')
            ->whereIn('role', ['registry_clerk', 'department_head'])
            ->update(['role' => 'user']);
        
        // Then modify the enum column
        DB::statement("ALTER TABLE employees MODIFY COLUMN role ENUM('admin', 'registry_head', 'user') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE employees MODIFY COLUMN role ENUM('admin', 'registry_head', 'registry_clerk', 'department_head', 'user') NOT NULL DEFAULT 'user'");
    }
};
