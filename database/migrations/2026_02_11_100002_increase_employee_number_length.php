<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function ($table) {
            $table->string('employee_number', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function ($table) {
            $table->string('employee_number', 20)->change();
        });
    }
};
