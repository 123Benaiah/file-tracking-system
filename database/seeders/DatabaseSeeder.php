<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Registry Head (MOHA ADMINISTRATION)
        $registryHead = Employee::create([
            'employee_number' => 'REGHEAD001',
            'name' => 'Registry Head',
            'email' => 'registry.head@moha.gov.zm',
            'password' => Hash::make('Moha@2024'),
            'position' => 'Assistant Director - Administration',
            'department' => 'Administration',
            'unit' => 'Registry',
            'office' => 'Registry Office',
            'role' => 'registry_head',
            'is_active' => true,
        ]);

        // Create Registry Clerk
        Employee::create([
            'employee_number' => 'REGCLK001',
            'name' => 'Registry Clerk',
            'email' => 'registry.clerk@moha.gov.zm',
            'password' => Hash::make('Clerk@2024'),
            'position' => 'Stenographer',
            'department' => 'Administration',
            'unit' => 'Registry',
            'office' => 'Registry Office',
            'role' => 'registry_clerk',
            'is_active' => true,
            'created_by' => $registryHead->employee_number,
        ]);

        // Create sample employees from provided data
        $employees = [
            [
                'employee_number' => 'EMP001',
                'name' => 'MOSTIN HAMOONGA',
                'email' => 'mostin.hamoonga@moha.gov.zm',
                'password' => Hash::make('Password123'),
                'position' => 'PERSONAL SECRETARY',
                'department' => 'HEADQUARTERS - MOHA',
                'office' => 'Headquarters Office',
                'role' => 'department_head',
                'is_active' => true,
                'created_by' => $registryHead->employee_number,
            ],
            [
                'employee_number' => 'EMP002',
                'name' => 'GRACE KYEMBE',
                'email' => 'grace.kyembe@moha.gov.zm',
                'password' => Hash::make('Password123'),
                'position' => 'LEGAL OFFICER',
                'department' => 'HEADQUARTERS - MOHA',
                'office' => 'Legal Office',
                'role' => 'department_head',
                'is_active' => true,
                'created_by' => $registryHead->employee_number,
            ],
            [
                'employee_number' => 'EMP003',
                'name' => 'IGNATIUS MWALA',
                'email' => 'ignatius.mwala@moha.gov.zm',
                'password' => Hash::make('Password123'),
                'position' => 'PUBLIC RELATIONS OFFICER',
                'department' => 'OFFICE OF THE MINISTER - HOME AFFAIRS',
                'office' => 'Minister\'s Office',
                'role' => 'department_head',
                'is_active' => true,
                'created_by' => $registryHead->employee_number,
            ],
            [
                'employee_number' => 'EMP004',
                'name' => 'ANDREW ZIMBA',
                'email' => 'andrew.zimba@moha.gov.zm',
                'password' => Hash::make('Password123'),
                'position' => 'Administration Officer',
                'department' => 'ADMINISTRATION MOHA',
                'office' => 'Administration Office',
                'role' => 'user',
                'is_active' => true,
                'created_by' => $registryHead->employee_number,
            ],
            [
                'employee_number' => 'EMP005',
                'name' => 'MUSYANI SINKALA',
                'email' => 'musyani.sinkala@moha.gov.zm',
                'password' => Hash::make('Password123'),
                'position' => 'ASSISTANT DIRECTOR - ADMINISTRATION',
                'department' => 'ADMINISTRATION MOHA',
                'office' => 'Director\'s Office',
                'role' => 'department_head',
                'is_active' => true,
                'created_by' => $registryHead->employee_number,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        // Create sample files
        $files = [
            [
                'subject' => 'HEAD OF STATE',
                'file_title' => 'STATE HOUSE MATTERS',
                'old_file_no' => 'MHA/1',
                'new_file_no' => 'MHA/1/1/1',
                'priority' => 'normal',
                'status' => 'at_registry',
                'confidentiality' => 'confidential',
                'remarks' => 'Presidential correspondence and state matters',
                'date_registered' => now()->format('Y-m-d'),
                'current_holder' => $registryHead->employee_number,
                'registered_by' => $registryHead->employee_number,
            ],
            [
                'subject' => 'PREROGATIVE OF MERCY',
                'file_title' => 'PREROGATIVE OF MERCY',
                'old_file_no' => 'MHA/1/3/1',
                'new_file_no' => 'MHA/1/3/1',
                'priority' => 'urgent',
                'status' => 'received',
                'confidentiality' => 'secret',
                'remarks' => 'Presidential mercy applications',
                'date_registered' => now()->subDays(5)->format('Y-m-d'),
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'current_holder' => 'EMP002',
                'registered_by' => $registryHead->employee_number,
            ],
            [
                'subject' => 'PUBLIC SERVICE MANAGEMENT POLICY',
                'file_title' => 'PUBLIC SERVICE HUMAN RESOURCE MANAGEMENT POLICY',
                'old_file_no' => 'MHA/1/6/1',
                'new_file_no' => 'MHA/1/6/1',
                'priority' => 'normal',
                'status' => 'in_transit',
                'confidentiality' => 'public',
                'remarks' => 'HR policy review and updates',
                'date_registered' => now()->subDays(2)->format('Y-m-d'),
                'current_holder' => null,
                'registered_by' => $registryHead->employee_number,
            ],
        ];

        foreach ($files as $file) {
            \App\Models\File::create($file);
        }
    }

}
// INSERT INTO employees (
//     employee_number,
//     name,
//     email,
//     password,
//     position,
//     department,
//     office,
//     role,
//     is_active,
//     created_at,
//     updated_at
// ) VALUES (
//     'EMP001',
//     'Benaiah Lushomo',
//     'benaiahlushomo@gmail.com',
//     SHA2('password123', 256),
//     'Staff',
//     'IT',
//     'Main Office',
//     'user',
//     1,
//     NOW(),
//     NOW()
// );
