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

                    // User Approval — onboarding new hires, approving/
                    // rejecting self-registrations.
                    'approve-users',
                    'reject-users',
                    'onboard-users',

                    // User Access — viewing/assigning roles, activating or
                    // suspending accounts, resetting forgotten passwords.
                    // Deliberately NOT given manage-roles/manage-permissions
                    // (role/permission *definitions* stay Administrator-only).
                    'view-user-access',
                    'assign-roles',
                    'manage-user-status',
                    'reset-user-passwords',

                    // Leave Management — general (all-employee) leave admin,
                    // a real HR function that was previously locked to the
                    // legacy supervisor account only.
                    'approve-leave-requests',

                    // DTR Approval — final stage of the Utility DTR pipeline
                    // (Employee verifies -> Mark checks -> HR approves).
                    'approve-dtr',

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
                    | Material Requests
                    |--------------------------------------------------------------------------
                    */

                    'process-material-requests',

                    /*
                    |--------------------------------------------------------------------------
                    | Sports Equipment Borrowing
                    |--------------------------------------------------------------------------
                    */

                    'manage-sports-equipment-inventory',

                    /*
                    |--------------------------------------------------------------------------
                    | User Access
                    |--------------------------------------------------------------------------
                    */

                    'reset-user-passwords',

                    /*
                    |--------------------------------------------------------------------------
                    | Reports
                    |--------------------------------------------------------------------------
                    */

                    'view-reports',
                    'print-reports',

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
                    'submit-ppmp',

                    'view-budget-monitoring',
                    'view-purchase-forecast',
                    'view-procurement-calendar',

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

        /*
        |--------------------------------------------------------------------------
        | Department Chair / Unit Head
        |--------------------------------------------------------------------------
        */

        $departmentChair = Role::where('name', 'Department Chair / Unit Head')->first();

        if ($departmentChair) {

            $departmentChair->permissions()->sync(

                Permission::whereIn('slug', [

                    'manage-own-department-ppmp-items',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | General Services Officer
        |--------------------------------------------------------------------------
        |
        | Oversees utility personnel and electrical/maintenance staff.
        |
        */

        $gso = Role::where('name', 'General Services Officer')->first();

        if ($gso) {

            $gso->permissions()->sync(

                Permission::whereIn('slug', [

                    'view-utility-staff',
                    'view-employee-profile',

                    'view-job-requests',
                    'approve-job-requests-utility',
                    'assign-job-request-personnel',

                    'manage-utility-schedule',
                    'manage-project-estimates',
                    'manage-building-inspections',
                    'approve-utility-leave',
                    'manage-water-bills',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Physical Plant and Services
        |--------------------------------------------------------------------------
        |
        | Approves job requests involving rehabilitation/repair of school
        | infrastructure, and assigns the maintenance crew to carry it out.
        |
        */

        $pps = Role::where('name', 'Physical Plant and Services')->first();

        if ($pps) {

            $pps->permissions()->sync(

                Permission::whereIn('slug', [

                    'view-utility-staff',
                    'view-employee-profile',

                    'view-job-requests',
                    'approve-job-requests-physical-plant',
                    'assign-job-request-personnel',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Property Custodian
        |--------------------------------------------------------------------------
        |
        | Maintains the Room Inventory of Property — fixed/durable property
        | (furniture, ICT equipment, appliances) tracked per room, separate
        | from the consumable Materials Inventory.
        |
        */

        $propertyCustodian = Role::where('name', 'Property Custodian')->first();

        if ($propertyCustodian) {

            $propertyCustodian->permissions()->sync(

                Permission::whereIn('slug', [

                    'manage-property-inventory',
                    'manage-property-issuance',
                    'approve-sports-equipment-borrows',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Energy Focal Person
        |--------------------------------------------------------------------------
        |
        | Prepares the Monthly Energy Conservation Report to DOE Main
        | Campus. Held by Mark as an additional role alongside GSO.
        |
        */

        $energyFocalPerson = Role::where('name', 'Energy Focal Person')->first();

        if ($energyFocalPerson) {

            $energyFocalPerson->permissions()->sync(

                Permission::whereIn('slug', [

                    'manage-energy-reports',

                ])->pluck('id')->toArray()

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Health Consultation — Health Service (Kris's primary role) and the
        | Nurse rank ladder (future nurses onboarded onto Nurse I-VII) both
        | get this, same "two roles share one permission" pattern as GSO/PPS
        | sharing Job Request assignment.
        |--------------------------------------------------------------------------
        */

        foreach (['Health Service', 'Nurse'] as $roleName) {

            $role = Role::where('name', $roleName)->first();

            if ($role) {

                $role->permissions()->sync(

                    Permission::whereIn('slug', [

                        'manage-health-consultations',
                        'manage-clinic-medicines',

                    ])->pluck('id')->toArray()

                );

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Admission and Testing Services — Anje's role, held with zero
        | permissions until now.
        |--------------------------------------------------------------------------
        */

        $admissionTesting = Role::where('name', 'Admission and Testing Services')->first();

        if ($admissionTesting) {

            $admissionTesting->permissions()->sync(

                Permission::whereIn('slug', [

                    'manage-admission-applicants',

                ])->pluck('id')->toArray()

            );

        }
    }
}