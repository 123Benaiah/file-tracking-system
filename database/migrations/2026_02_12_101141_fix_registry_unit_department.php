<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Find the HRA department (Human Resources and Administration)
        $hraDepartment = DB::table('departments')
            ->where('name', 'Human Resources and Administration')
            ->orWhere('code', 'HRA')
            ->first();

        if ($hraDepartment) {
            // Update the Registry unit to point to HRA department
            DB::table('units')
                ->where('name', 'Registry')
                ->update(['department_id' => $hraDepartment->id]);
            
            echo "Updated Registry unit to department ID: " . $hraDepartment->id . " (" . $hraDepartment->name . ")\n";
        } else {
            echo "HRA department not found!\n";
        }
    }

    public function down(): void
    {
        // This is a one-time fix, no rollback needed
    }
};
