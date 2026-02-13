<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function ($table) {
            $table->enum('role', ['admin', 'registry_head', 'registry_clerk', 'department_head', 'user'])->default('user')->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function ($table) {
            $table->enum('role', ['registry_head', 'registry_clerk', 'department_head', 'user'])->default('user')->change();
        });
    }
};
