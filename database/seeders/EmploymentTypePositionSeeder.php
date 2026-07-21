<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmploymentType;
use App\Models\Position;

class EmploymentTypePositionSeeder extends Seeder
{
    public function run(): void
    {
        $map = [

            /*
            |--------------------------------------------------------------------------
            | Plantilla (Regular) — same Academic Rank ladder as Temporary
            | and Contract of Service below.
            |--------------------------------------------------------------------------
            */

            'Plantilla (Regular)' => [

                'Instructor I',
                'Instructor II',
                'Instructor III',

                'Assistant Professor I',
                'Assistant Professor II',
                'Assistant Professor III',
                'Assistant Professor IV',

                'Associate Professor I',
                'Associate Professor II',
                'Associate Professor III',
                'Associate Professor IV',
                'Associate Professor V',

                'Professor I',
                'Professor II',
                'Professor III',
                'Professor IV',
                'Professor V',
                'Professor VI',

                'University Professor',

            ],

            /*
            |--------------------------------------------------------------------------
            | Plantilla (Temporary) — same Academic Rank ladder as Regular.
            |--------------------------------------------------------------------------
            */

            'Plantilla (Temporary)' => [

                'Instructor I',
                'Instructor II',
                'Instructor III',

                'Assistant Professor I',
                'Assistant Professor II',
                'Assistant Professor III',
                'Assistant Professor IV',

                'Associate Professor I',
                'Associate Professor II',
                'Associate Professor III',
                'Associate Professor IV',
                'Associate Professor V',

                'Professor I',
                'Professor II',
                'Professor III',
                'Professor IV',
                'Professor V',
                'Professor VI',

                'University Professor',

            ],

            /*
            |--------------------------------------------------------------------------
            | Contract of Service (COS) — same Academic Rank ladder, plus a
            | few COS-specific designations (Lecturer, Laboratory Instructor,
            | Part-time Instructor) already established for this type.
            |--------------------------------------------------------------------------
            */

            'Contract of Service (COS)' => [

                'Instructor I',
                'Instructor II',
                'Instructor III',

                'Assistant Professor I',
                'Assistant Professor II',
                'Assistant Professor III',
                'Assistant Professor IV',

                'Associate Professor I',
                'Associate Professor II',
                'Associate Professor III',
                'Associate Professor IV',
                'Associate Professor V',

                'Professor I',
                'Professor II',
                'Professor III',
                'Professor IV',
                'Professor V',
                'Professor VI',

                'Lecturer',
                'Laboratory Instructor',
                'Part-time Instructor',

            ],

            // Administrative Personnel, Non-Teaching Personnel, and Utility
            // Personnel employment types were removed entirely — everyone on
            // them moved to Job Order. HR Officer/Accountant/Cashier/Supply
            // Officer/Registrar Staff/Library Staff/Guidance Staff/IT
            // Officer/Research Assistant had no personnel on them at the time
            // and are left unlinked to any employment type for now; Nurse/
            // Utility Worker/Groundskeeper/Maintenance Worker (which DID have
            // real personnel) were moved under Job Order below.

            /*
            |--------------------------------------------------------------------------
            | Contractual Personnel, Casual Personnel, Job Order — share the
            | same designation range (Administrative Aide/Assistant/Officer),
            | on top of their own existing project-based positions.
            |--------------------------------------------------------------------------
            */

            'Contractual Personnel' => [

                'Project Staff',
                'Technical Assistant',
                'Laboratory Assistant',

                'Office Assistant',

                'Administrative Aide I',
                'Administrative Aide II',
                'Administrative Aide III',
                'Administrative Aide IV',
                'Administrative Aide VI',

                'Administrative Assistant I',
                'Administrative Assistant II',
                'Administrative Assistant III',

                'Administrative Officer I',
                'Administrative Officer II',
                'Administrative Officer III',
                'Administrative Officer IV',
                'Administrative Officer V',

            ],

            'Casual Personnel' => [

                'Office Assistant',
                'Office Helper',
                'Driver',
                'Security Aide',

                'Administrative Aide I',
                'Administrative Aide II',
                'Administrative Aide III',
                'Administrative Aide IV',
                'Administrative Aide VI',

                'Administrative Assistant I',
                'Administrative Assistant II',
                'Administrative Assistant III',

                'Administrative Officer I',
                'Administrative Officer II',
                'Administrative Officer III',
                'Administrative Officer IV',
                'Administrative Officer V',

            ],

            'Job Order' => [

                'Office Assistant',
                'Office Helper',
                'Driver',
                'Security Aide',
                'Project Staff',

                'Administrative Aide I',
                'Administrative Aide II',
                'Administrative Aide III',
                'Administrative Aide IV',
                'Administrative Aide VI',

                'Administrative Assistant I',
                'Administrative Assistant II',
                'Administrative Assistant III',

                'Administrative Officer I',
                'Administrative Officer II',
                'Administrative Officer III',
                'Administrative Officer IV',
                'Administrative Officer V',

                // Absorbed from the removed Administrative/Non-Teaching/
                // Utility Personnel employment types.
                'Nurse',
                'Utility Worker',
                'Groundskeeper',
                'Maintenance Worker',

            ],

        ];

        foreach ($map as $employmentName => $positions) {

            $employmentType = EmploymentType::where(
                'name',
                $employmentName
            )->first();

            if (! $employmentType) {
                continue;
            }

            foreach ($positions as $positionName) {

                $position = Position::where(
                    'position_name',
                    $positionName
                )->first();

                if ($position) {

                    $employmentType
                        ->positions()
                        ->syncWithoutDetaching([
                            $position->id
                        ]);

                }

            }

        }
    }
}