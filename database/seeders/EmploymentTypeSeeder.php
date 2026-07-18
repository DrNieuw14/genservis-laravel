<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employment_types')->insert([
            ['name' => 'Regular Faculty'],
            ['name' => 'COS Faculty'],
            ['name' => 'Administrative Personnel'],
            ['name' => 'Non-Teaching Personnel'],
            ['name' => 'Utility Personnel'],
            ['name' => 'Contractual Personnel'],
        ]);
    }
}