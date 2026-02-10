<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns if they don't exist
        if (!Schema::hasColumn('employees', 'department_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()
                      ->constrained('departments')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('employees', 'unit_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('unit_id')->nullable()
                      ->constrained('units')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['department_id', 'unit_id']);
        });
    }
};
