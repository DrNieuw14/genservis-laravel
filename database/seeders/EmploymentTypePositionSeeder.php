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
            | Regular Faculty
            |--------------------------------------------------------------------------
            */

            'Regular Faculty' => [

                'Instructor I',
                'Instructor II',
                'Instructor III',

                'Assistant Professor I',
                'Assistant Professor II',
                'Assistant Professor III',

                'Associate Professor I',
                'Associate Professor II',
                'Associate Professor III',

                'Professor I',
                'Professor II',
                'Professor III',

            ],

            /*
            |--------------------------------------------------------------------------
            | COS Faculty
            |--------------------------------------------------------------------------
            */

            'COS Faculty' => [

                'Instructor I',
                'Instructor II',
                'Instructor III',

                'Assistant Professor I',
                'Assistant Professor II',
                'Assistant Professor III',

                'Associate Professor I',
                'Associate Professor II',
                'Associate Professor III',

                'Professor I',
                'Professor II',
                'Professor III',

                'Lecturer',
                'Laboratory Instructor',
                'Part-time Instructor',

            ],

            /*
            |--------------------------------------------------------------------------
            | Administrative Personnel
            |--------------------------------------------------------------------------
            */

            'Administrative Personnel' => [

                'Administrative Aide I',
                'Administrative Aide II',

                'Administrative Assistant I',

                'Administrative Officer I',
                'Administrative Officer II',

                'HR Officer',

                'Accountant',

                'Cashier',

                'Supply Officer',

            ],

            /*
            |--------------------------------------------------------------------------
            | Non-Teaching Personnel
            |--------------------------------------------------------------------------
            */

            'Non-Teaching Personnel' => [

                'Registrar Staff',
                'Library Staff',
                'Guidance Staff',

                'IT Officer',

                'Research Assistant',

            ],

            /*
            |--------------------------------------------------------------------------
            | Utility Personnel
            |--------------------------------------------------------------------------
            */

            'Utility Personnel' => [

                'Utility Worker',
                'Groundskeeper',
                'Maintenance Worker',

            ],

            /*
            |--------------------------------------------------------------------------
            | Job Order
            |--------------------------------------------------------------------------
            */

            'Job Order' => [

                'Office Assistant',
                'Driver',
                'Security Aide',
                'Office Helper',

            ],

            /*
            |--------------------------------------------------------------------------
            | Contractual Personnel
            |--------------------------------------------------------------------------
            */

            'Contractual Personnel' => [

                'Project Staff',
                'Technical Assistant',
                'Laboratory Assistant',

                'Office Assistant',

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