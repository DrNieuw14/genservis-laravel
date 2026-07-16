<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $modules = [

            /*
            |--------------------------------------------------------------------------
            | Employee Management
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Employee Management',

                'permissions' => [

                    ['name' => 'View Employees', 'slug' => 'view-employees'],
                    ['name' => 'Create Employees', 'slug' => 'create-employees'],
                    ['name' => 'Edit Employees', 'slug' => 'edit-employees'],
                    ['name' => 'Delete Employees', 'slug' => 'delete-employees'],

                    ['name' => 'View Employee Profile', 'slug' => 'view-employee-profile'],
                    ['name' => 'Edit Employee Profile', 'slug' => 'edit-employee-profile'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Inventory',

                'permissions' => [

                    // Materials
                    ['name' => 'View Materials', 'slug' => 'view-materials'],
                    ['name' => 'Create Materials', 'slug' => 'create-materials'],
                    ['name' => 'Edit Materials', 'slug' => 'edit-materials'],
                    ['name' => 'Delete Materials', 'slug' => 'delete-materials'],
                    ['name' => 'Restock Materials', 'slug' => 'restock-materials'],

                    // Categories
                    ['name' => 'View Categories', 'slug' => 'view-categories'],
                    ['name' => 'Create Categories', 'slug' => 'create-categories'],
                    ['name' => 'Edit Categories', 'slug' => 'edit-categories'],
                    ['name' => 'Delete Categories', 'slug' => 'delete-categories'],

                    // Units
                    ['name' => 'View Units', 'slug' => 'view-units'],
                    ['name' => 'Create Units', 'slug' => 'create-units'],
                    ['name' => 'Edit Units', 'slug' => 'edit-units'],
                    ['name' => 'Delete Units', 'slug' => 'delete-units'],

                    // Departments
                    ['name' => 'View Departments', 'slug' => 'view-departments'],
                    ['name' => 'Create Departments', 'slug' => 'create-departments'],
                    ['name' => 'Edit Departments', 'slug' => 'edit-departments'],
                    ['name' => 'Delete Departments', 'slug' => 'delete-departments'],

                    // Others
                    ['name' => 'View Inventory Movements', 'slug' => 'view-inventory-movements'],
                    ['name' => 'View Department Inventory', 'slug' => 'view-department-inventory'],
                    ['name' => 'View Material Logs', 'slug' => 'view-material-logs'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Procurement
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Procurement',

                'permissions' => [

                    ['name' => 'View PPMP', 'slug' => 'view-ppmp'],
                    ['name' => 'Create PPMP', 'slug' => 'create-ppmp'],
                    ['name' => 'Edit PPMP', 'slug' => 'edit-ppmp'],
                    ['name' => 'Delete PPMP', 'slug' => 'delete-ppmp'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Walk-In Issuance
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Walk-In Issuance',

                'permissions' => [

                    ['name' => 'View Walk-In Requests', 'slug' => 'view-walkin-requests'],
                    ['name' => 'Create Walk-In Requests', 'slug' => 'create-walkin-requests'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Reports',

                'permissions' => [

                    ['name' => 'View Reports', 'slug' => 'view-reports'],
                    ['name' => 'Export Reports', 'slug' => 'export-reports'],
                    ['name' => 'Print Reports', 'slug' => 'print-reports'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | User Access Management
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'User Access',

                'permissions' => [

                    /*
                    |--------------------------------------------------------------------------
                    | User Access Dashboard
                    |--------------------------------------------------------------------------
                    */

                    ['name' => 'View User Access', 'slug' => 'view-user-access'],

                    /*
                    |--------------------------------------------------------------------------
                    | User Approval
                    |--------------------------------------------------------------------------
                    */

                    ['name' => 'Approve Users', 'slug' => 'approve-users'],
                    ['name' => 'Reject Users', 'slug' => 'reject-users'],
                    ['name' => 'Onboard Users', 'slug' => 'onboard-users'],

                    /*
                    |--------------------------------------------------------------------------
                    | User Administration
                    |--------------------------------------------------------------------------
                    */

                    ['name' => 'Assign Roles', 'slug' => 'assign-roles'],
                    ['name' => 'Manage Roles', 'slug' => 'manage-roles'],
                    ['name' => 'Manage Permissions', 'slug' => 'manage-permissions'],
                    ['name' => 'Manage User Status', 'slug' => 'manage-user-status'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | System Administration
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'System Administration',

                'permissions' => [

                    ['name' => 'View Activity Logs', 'slug' => 'view-activity-logs'],
                    ['name' => 'Manage System Settings', 'slug' => 'manage-system-settings'],

                ]
            ],

        ];

        foreach ($modules as $module) {

            foreach ($module['permissions'] as $permission) {

                Permission::firstOrCreate(

                    [
                        'slug' => $permission['slug']
                    ],

                    [
                        'module' => $module['module'],
                        'name' => $permission['name'],
                        'description' => null,
                        'status' => true,
                    ]

                );

            }

        }
    }
}