<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Employee::where('employee_number', 'Mohais_File_Tracking_Admin')->first();

        if (!$admin) {
            Employee::create([
                'employee_number' => 'Mohais_File_Tracking_Admin',
                'name' => 'System Administrator',
                'email' => 'admin@moha.gov.rw',
                'password' => Hash::make('Found!t'),
                'role' => 'admin',
                'is_active' => true,
                'office' => 'MOHA Headquarters',
                'created_by' => null,
                'position_id' => null,
                'department_id' => null,
                'unit_id' => null,
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Employee Number: Mohais_File_Tracking_Admin');
            $this->command->info('Password: Found!t');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
