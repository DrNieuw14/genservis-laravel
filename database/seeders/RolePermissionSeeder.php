<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       /*
        |--------------------------------------------------------------------------
        | Administrator
        |--------------------------------------------------------------------------
        |
        | Give Administrator every permission.
        |
        */

        $administrator = Role::where('name', 'Administrator')->first();

        if ($administrator) {

            $administrator->permissions()->sync(
                Permission::pluck('id')->toArray()
            );

        }

        /*
        |--------------------------------------------------------------------------
        | HR Officer
        |--------------------------------------------------------------------------
        */

        $hr = Role::where('name', 'HR Officer')->first();

        if ($hr) {

            $hr->permissions()->sync(

                Permission::whereIn('slug', [

                    'view-employees',
                    'create-employees',
                    'edit-employees',
                    'delete-employees',

                    'view-employee-profile',
                    'edit-employee-profile',

                    'view-reports',
                    'export-reports',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Inventory Custodian
        |--------------------------------------------------------------------------
        */

        $inventory = Role::where('name', 'Inventory Custodian')->first();

        if ($inventory) {

            $inventory->permissions()->sync(

                Permission::whereIn('slug', [

                    /*
                    |--------------------------------------------------------------------------
                    | Materials
                    |--------------------------------------------------------------------------
                    */

                     'view-materials',
                     'create-materials',
                     'edit-materials',
                     'delete-materials',
                     'restock-materials',
                     'view-material-logs',

                    /*
                    |--------------------------------------------------------------------------
                    | Categories
                    |--------------------------------------------------------------------------
                    */

                     'view-categories',
                    'create-categories',
                    'edit-categories',
                    'delete-categories',

                    /*
                    |--------------------------------------------------------------------------
                    | Units
                    |--------------------------------------------------------------------------
                    */

                    'view-units',
                    'create-units',
                    'edit-units',
                    'delete-units',

                    /*
                    |--------------------------------------------------------------------------
                    | Departments
                    |--------------------------------------------------------------------------
                    */

                    'view-departments',
                    'create-departments',
                    'edit-departments',
                    'delete-departments',

                    /*
                    |--------------------------------------------------------------------------
                    | Inventory
                    |--------------------------------------------------------------------------
                    */

                    'view-inventory-movements',
                    'view-department-inventory',
                    'view-material-logs',

                    /*
                    |--------------------------------------------------------------------------
                    | Walk-In Issuance
                    |--------------------------------------------------------------------------
                    */

                    'view-walkin-requests',
                    'create-walkin-requests',

                    /*
                    |--------------------------------------------------------------------------
                    | Reports
                    |--------------------------------------------------------------------------
                    */

                    'view-reports',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Procurement Officer
        |--------------------------------------------------------------------------
        */

        $procurement = Role::where('name', 'Procurement Officer')->first();

        if ($procurement) {

            $procurement->permissions()->sync(

                Permission::whereIn('slug', [

                    'view-ppmp',
                    'create-ppmp',
                    'edit-ppmp',

                    'view-reports',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        $employee = Role::where('name', 'Employee')->first();

        if ($employee) {

            $employee->permissions()->sync(

                Permission::whereIn('slug', [

                    'view-employee-profile',

                ])->pluck('id')->toArray()

            );

        }
    }
}