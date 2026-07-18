<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [

            // Faculty
            ['position_name' => 'Instructor I', 'position_code' => 'INST1'],
            ['position_name' => 'Instructor II', 'position_code' => 'INST2'],
            ['position_name' => 'Instructor III', 'position_code' => 'INST3'],

            ['position_name' => 'Assistant Professor I', 'position_code' => 'AP1'],
            ['position_name' => 'Assistant Professor II', 'position_code' => 'AP2'],
            ['position_name' => 'Assistant Professor III', 'position_code' => 'AP3'],
            ['position_name' => 'Assistant Professor IV', 'position_code' => 'AP4'],

            ['position_name' => 'Associate Professor I', 'position_code' => 'ASP1'],
            ['position_name' => 'Associate Professor II', 'position_code' => 'ASP2'],
            ['position_name' => 'Associate Professor III', 'position_code' => 'ASP3'],
            ['position_name' => 'Associate Professor IV', 'position_code' => 'ASP4'],
            ['position_name' => 'Associate Professor V', 'position_code' => 'ASP5'],

            ['position_name' => 'Professor I', 'position_code' => 'PROF1'],
            ['position_name' => 'Professor II', 'position_code' => 'PROF2'],
            ['position_name' => 'Professor III', 'position_code' => 'PROF3'],
            ['position_name' => 'Professor IV', 'position_code' => 'PROF4'],
            ['position_name' => 'Professor V', 'position_code' => 'PROF5'],
            ['position_name' => 'Professor VI', 'position_code' => 'PROF6'],

            ['position_name' => 'University Professor', 'position_code' => 'UNIVPROF'],

            ['position_name' => 'Lecturer', 'position_code' => 'LECT'],
            ['position_name' => 'Laboratory Instructor', 'position_code' => 'LABINST'],
            ['position_name' => 'Part-time Instructor', 'position_code' => 'PTINST'],

            // Administrative
            ['position_name' => 'Administrative Aide I', 'position_code' => 'AA1'],
            ['position_name' => 'Administrative Aide II', 'position_code' => 'AA2'],
            ['position_name' => 'Administrative Assistant I', 'position_code' => 'AAS1'],
            ['position_name' => 'Administrative Officer I', 'position_code' => 'AO1'],
            ['position_name' => 'Administrative Officer II', 'position_code' => 'AO2'],
            ['position_name' => 'HR Officer', 'position_code' => 'HRO'],
            ['position_name' => 'Accountant', 'position_code' => 'ACC'],
            ['position_name' => 'Cashier', 'position_code' => 'CASH'],
            ['position_name' => 'Supply Officer', 'position_code' => 'SUP'],

            // Non-Teaching
            ['position_name' => 'Registrar Staff', 'position_code' => 'REG'],
            ['position_name' => 'Library Staff', 'position_code' => 'LIB'],
            ['position_name' => 'Guidance Staff', 'position_code' => 'GUIDE'],
            ['position_name' => 'IT Officer', 'position_code' => 'IT'],
            ['position_name' => 'Research Assistant', 'position_code' => 'RA'],
            ['position_name' => 'Nurse', 'position_code' => 'NURSE'],

            // Utility
            ['position_name' => 'Utility Worker', 'position_code' => 'UTIL'],
            ['position_name' => 'Groundskeeper', 'position_code' => 'GROUND'],
            ['position_name' => 'Maintenance Worker', 'position_code' => 'MAIN'],

            // Job Order / Contractual
            ['position_name' => 'Office Assistant', 'position_code' => 'OA'],
            ['position_name' => 'Driver', 'position_code' => 'DRV'],
            ['position_name' => 'Security Aide', 'position_code' => 'SEC'],
            ['position_name' => 'Office Helper', 'position_code' => 'HELP'],
            ['position_name' => 'Project Staff', 'position_code' => 'PS'],
            ['position_name' => 'Technical Assistant', 'position_code' => 'TA'],
            ['position_name' => 'Laboratory Assistant', 'position_code' => 'LABA'],
        ];

        foreach ($positions as $position) {

            Position::updateOrCreate(
                ['position_name' => $position['position_name']],
                [
                    'position_code' => $position['position_code'],
                    'is_active' => 1,
                ]
            );

        }
    }
}