<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class MohaisOrganizationalStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Create positions
        $positions = [
            // Director level (Department heads)
            ['title' => 'Director, Human Resources and Administration', 'code' => 'DIR-HRA', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, National Registration, Passport and Citizenship', 'code' => 'DIR-NRPC', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, National Archives', 'code' => 'DIR-NA', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Commissioner for Refugees', 'code' => 'DIR-CFR', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Registrar of Societies', 'code' => 'DIR-RS', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Home Affairs Planning and Information', 'code' => 'DIR-HAPI', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, ICT', 'code' => 'DIR-ICT', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Internal Audit', 'code' => 'DIR-IA', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Finance', 'code' => 'DIR-FIN', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Police Public Complaint Commission', 'code' => 'DIR-PPCC', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, National Forensic Authority', 'code' => 'DIR-NFA', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Office of the State Forensic Pathologist', 'code' => 'DIR-OSFP', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, National Forensic Science & Biometric', 'code' => 'DIR-NFSB', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, Anti-Human Trafficking', 'code' => 'DIR-AHT', 'position_type' => 'director', 'level' => 10],
            ['title' => 'Director, National Anti-Terrorism Centre', 'code' => 'DIR-NATC', 'position_type' => 'director', 'level' => 10],
            
            // Assistant Director level (Unit heads)
            ['title' => 'Assistant Director (various units)', 'code' => 'AD-UNIT', 'position_type' => 'assistant_director', 'level' => 8],
            
            // Supervisor level
            ['title' => 'Supervisor', 'code' => 'SUP', 'position_type' => 'supervisor', 'level' => 6],
            
            // Staff level
            ['title' => 'Senior Officer', 'code' => 'SO', 'position_type' => 'staff', 'level' => 4],
            ['title' => 'Officer', 'code' => 'OFF', 'position_type' => 'staff', 'level' => 3],
            ['title' => 'Junior Officer', 'code' => 'JO', 'position_type' => 'staff', 'level' => 2],
            ['title' => 'Assistant', 'code' => 'AST', 'position_type' => 'staff', 'level' => 1],
            
            // Support level
            ['title' => 'Administrative Assistant', 'code' => 'AA', 'position_type' => 'support', 'level' => 1],
            ['title' => 'Secretary', 'code' => 'SEC', 'position_type' => 'support', 'level' => 1],
            ['title' => 'Clerk', 'code' => 'CLK', 'position_type' => 'support', 'level' => 1],
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(
                ['code' => $position['code']],
                $position
            );
        }

        // Create departments
        $departments = [
            [
                'name' => 'Human Resources and Administration',
                'code' => 'HRA',
                'description' => 'Handles human resources management and administrative functions',
                'has_units' => true,
            ],
            [
                'name' => 'National Registration, Passport and Citizenship',
                'code' => 'NRPC',
                'description' => 'Manages national registration, passport issuance, and citizenship services',
                'has_units' => true,
            ],
            [
                'name' => 'National Archives',
                'code' => 'NA',
                'description' => 'Preserves and manages national archival records',
                'has_units' => true,
            ],
            [
                'name' => 'Commissioner for Refugees',
                'code' => 'CFR',
                'description' => 'Handles refugee affairs and protection',
                'has_units' => true,
            ],
            [
                'name' => 'Registrar of Societies',
                'code' => 'RS',
                'description' => 'Regulates and registers societies and organizations',
                'has_units' => false,
            ],
            [
                'name' => 'Home Affairs Planning and Information',
                'code' => 'HAPI',
                'description' => 'Strategic planning and information management',
                'has_units' => true,
            ],
            [
                'name' => 'Information and Communication Technology (ICT)',
                'code' => 'ICT',
                'description' => 'Manages IT infrastructure and digital services',
                'has_units' => true,
            ],
            [
                'name' => 'Internal Audit',
                'code' => 'IA',
                'description' => 'Conducts internal audits and compliance reviews',
                'has_units' => false,
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Manages financial operations and budgeting',
                'has_units' => true,
            ],
            [
                'name' => 'Police Public Complaint Commission',
                'code' => 'PPCC',
                'description' => 'Handles public complaints against police',
                'has_units' => false,
            ],
            [
                'name' => 'National Forensic Authority',
                'code' => 'NFA',
                'description' => 'Provides forensic science services',
                'has_units' => true,
            ],
            [
                'name' => 'Office of the State Forensic Pathologist',
                'code' => 'OSFP',
                'description' => 'Forensic pathology services',
                'has_units' => false,
            ],
            [
                'name' => 'National Forensic Science & Biometric',
                'code' => 'NFSB',
                'description' => 'Forensic science and biometric services',
                'has_units' => true,
            ],
            [
                'name' => 'Anti-Human Trafficking',
                'code' => 'AHT',
                'description' => 'Combats human trafficking activities',
                'has_units' => false,
            ],
            [
                'name' => 'National Anti-Terrorism Centre',
                'code' => 'NATC',
                'description' => 'Coordinates anti-terrorism efforts',
                'has_units' => false,
            ],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['code' => $dept['code']],
                $dept
            );
        }

        // Create sample units for departments that have units
        $units = [
            // HRA Units
            ['name' => 'Recruitment Unit', 'code' => 'HRA-REC', 'department_code' => 'HRA'],
            ['name' => 'Training Unit', 'code' => 'HRA-TRN', 'department_code' => 'HRA'],
            ['name' => 'Employee Services Unit', 'code' => 'HRA-ESV', 'department_code' => 'HRA'],
            
            // NRPC Units
            ['name' => 'Passport Unit', 'code' => 'NRPC-PAS', 'department_code' => 'NRPC'],
            ['name' => 'Citizenship Unit', 'code' => 'NRPC-CIT', 'department_code' => 'NRPC'],
            ['name' => 'Registration Unit', 'code' => 'NRPC-REG', 'department_code' => 'NRPC'],
            
            // Finance Units
            ['name' => 'Budget Unit', 'code' => 'FIN-BUD', 'department_code' => 'FIN'],
            ['name' => 'Accounts Unit', 'code' => 'FIN-ACC', 'department_code' => 'FIN'],
            ['name' => 'Payroll Unit', 'code' => 'FIN-PAY', 'department_code' => 'FIN'],
            
            // ICT Units
            ['name' => 'Infrastructure Unit', 'code' => 'ICT-INF', 'department_code' => 'ICT'],
            ['name' => 'Software Development Unit', 'code' => 'ICT-SWD', 'department_code' => 'ICT'],
            ['name' => 'Support Unit', 'code' => 'ICT-SUP', 'department_code' => 'ICT'],
            
            // NFA Units
            ['name' => 'DNA Unit', 'code' => 'NFA-DNA', 'department_code' => 'NFA'],
            ['name' => 'Fingerprint Unit', 'code' => 'NFA-FPR', 'department_code' => 'NFA'],
            ['name' => 'Crime Lab Unit', 'code' => 'NFA-CRL', 'department_code' => 'NFA'],
            
            // NFSB Units
            ['name' => 'Biometric Unit', 'code' => 'NFSB-BIO', 'department_code' => 'NFSB'],
            ['name' => 'Forensic Analysis Unit', 'code' => 'NFSB-FAU', 'department_code' => 'NFSB'],
        ];

        foreach ($units as $unit) {
            $department = Department::where('code', $unit['department_code'])->first();
            if ($department) {
                Unit::firstOrCreate(
                    ['code' => $unit['code']],
                    [
                        'name' => $unit['name'],
                        'department_id' => $department->id,
                    ]
                );
            }
        }
    }
}
