<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmploymentType;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Plantilla — permanent teaching items, split Regular/Temporary
            // per civil service appointment status.
            ['name' => 'Plantilla (Regular)', 'employee_prefix' => 'REGF'],
            ['name' => 'Plantilla (Temporary)', 'employee_prefix' => 'TEMP'],

            // Contract of Service — non-plantilla teaching personnel.
            ['name' => 'Contract of Service (COS)', 'employee_prefix' => 'COSF'],

            // Administrative Personnel, Non-Teaching Personnel, and Utility
            // Personnel were removed as employment statuses — anyone on them
            // was moved to Job Order, which also covers those designations
            // (Administrative Aide/Assistant/Officer, Nurse, Utility Worker,
            // Groundskeeper, Maintenance Worker) via EmploymentTypePositionSeeder.
            ['name' => 'Contractual Personnel', 'employee_prefix' => 'CONT'],
            ['name' => 'Job Order', 'employee_prefix' => 'JO'],
            ['name' => 'Casual Personnel', 'employee_prefix' => 'CAS'],
        ];

        foreach ($types as $type) {

            EmploymentType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'employee_prefix' => $type['employee_prefix'],
                    'is_active' => true,
                ]
            );

        }
    }
}