<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'description' => 'Administrative access'
            ],

            [
                'name' => 'HR Officer',
                'description' => 'Human Resource Management'
            ],

            [
                'name' => 'Secretary',
                'description' => 'Department Secretary'
            ],

            [
                'name' => 'Inventory Custodian',
                'description' => 'Inventory Management'
            ],

            [
                'name' => 'General Services Officer',
                'description' => 'General Services'
            ],

            [
                'name' => 'Procurement Officer',
                'description' => 'Procurement'
            ],

            [
                'name' => 'Research Coordinator',
                'description' => 'Research'
            ],

            [
                'name' => 'Extension Coordinator',
                'description' => 'Extension'
            ],

            [
                'name' => 'Employee',
                'description' => 'Standard employee access'
            ]

        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}