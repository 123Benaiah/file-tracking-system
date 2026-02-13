<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_registry_department to departments table
        Schema::table('departments', function (Blueprint $table) {
            $table->boolean('is_registry_department')->default(false)->after('has_units');
        });

        // Add is_registry_unit to units table
        Schema::table('units', function (Blueprint $table) {
            $table->boolean('is_registry_unit')->default(false)->after('code');
        });

        // Add is_registry_head and is_registry_staff to employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('is_registry_head')->default(false)->after('role');
            $table->boolean('is_registry_staff')->default(false)->after('is_registry_head');
        });

        // Set HRA department as registry department
        DB::table('departments')->where('code', 'HRA')->update(['is_registry_department' => true]);

        // Set Registry unit under HRA as registry unit
        DB::table('units')
            ->where('name', 'Registry')
            ->whereIn('department_id', function ($query) {
                $query->select('id')->from('departments')->where('is_registry_department', true);
            })
            ->update(['is_registry_unit' => true]);

        // Set REGHEAD001 as registry head
        DB::table('employees')
            ->where('employee_number', 'REGHEAD001')
            ->update(['is_registry_head' => true]);
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('is_registry_department');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('is_registry_unit');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['is_registry_head', 'is_registry_staff']);
        });
    }
};
