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

                    ['name' => 'View Utility & Maintenance Staff', 'slug' => 'view-utility-staff'],

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

                    // Material Requests
                    ['name' => 'Process Material Requests', 'slug' => 'process-material-requests'],

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
                    ['name' => 'Submit PPMP', 'slug' => 'submit-ppmp'],
                    ['name' => 'Approve PPMP', 'slug' => 'approve-ppmp'],
                    ['name' => 'Reject PPMP', 'slug' => 'reject-ppmp'],
                    ['name' => 'View Budget Monitoring', 'slug' => 'view-budget-monitoring'],
                    ['name' => 'View Purchase Forecast', 'slug' => 'view-purchase-forecast'],
                    ['name' => 'View Procurement Calendar', 'slug' => 'view-procurement-calendar'],
                    ['name' => 'Manage Own Department PPMP Items', 'slug' => 'manage-own-department-ppmp-items'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Job Requests
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Job Requests',

                'permissions' => [

                    ['name' => 'View Job Requests', 'slug' => 'view-job-requests'],
                    ['name' => 'Approve Physical Plant Job Requests', 'slug' => 'approve-job-requests-physical-plant'],
                    ['name' => 'Approve Utility Job Requests', 'slug' => 'approve-job-requests-utility'],
                    ['name' => 'Assign Job Request Personnel', 'slug' => 'assign-job-request-personnel'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Utility Scheduling
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Utility Scheduling',

                'permissions' => [

                    ['name' => 'Manage Utility Schedule', 'slug' => 'manage-utility-schedule'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Project Detailed Estimates
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Project Detailed Estimates',

                'permissions' => [

                    ['name' => 'Manage Project Detailed Estimates', 'slug' => 'manage-project-estimates'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Building Inspections
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Building Inspections',

                'permissions' => [

                    ['name' => 'Manage Building Inspections', 'slug' => 'manage-building-inspections'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Utility Leave Requests
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Utility Leave Requests',

                'permissions' => [

                    ['name' => 'Approve Utility Leave', 'slug' => 'approve-utility-leave'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Leave Management — the general (all-employee) leave admin page,
            | distinct from Utility Leave Requests above which is scoped to
            | the Utility & Maintenance Staff pool only.
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Leave Management',

                'permissions' => [

                    ['name' => 'Approve Leave Requests', 'slug' => 'approve-leave-requests'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | DTR Approval — HR's final sign-off stage in the Utility DTR
            | pipeline (Employee verifies -> Mark checks -> HR approves).
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'DTR Approval',

                'permissions' => [

                    ['name' => 'Approve DTR', 'slug' => 'approve-dtr'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Energy Conservation Report — monthly report to the DOE Main
            | Campus, built from the real CvSU template. Energy Focal Person
            | only.
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Energy Conservation Report',

                'permissions' => [

                    ['name' => 'Manage Energy Reports', 'slug' => 'manage-energy-reports'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Water Bill Report — Carmona Water District billing notices,
            | tracked per meter/account. General Services Officer only.
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Water Bill Report',

                'permissions' => [

                    ['name' => 'Manage Water Bills', 'slug' => 'manage-water-bills'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Health Consultation — Campus Health Services clinic visit
            | record, built from the real CvSU Consultation Form. Held by
            | the Health Service role and the Nurse rank ladder.
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Health Consultation',

                'permissions' => [

                    ['name' => 'Manage Health Consultations', 'slug' => 'manage-health-consultations'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Clinic Medicine Inventory — stock of medicine/medical supplies
            | held by Campus Health Services, built from the real CvSU
            | Health Services stock sheet. Health Service / Nurse only.
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Clinic Medicine Inventory',

                'permissions' => [

                    ['name' => 'Manage Clinic Medicines', 'slug' => 'manage-clinic-medicines'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Admission Applicant Roster — imported from the real Admission
            | Testing registration export, organized per Admission Year.
            | Admission and Testing Services only.
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Admission Applicant Roster',

                'permissions' => [

                    ['name' => 'Manage Admission Applicants', 'slug' => 'manage-admission-applicants'],

                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Room Inventory of Property — fixed/durable property (furniture,
            | ICT equipment, appliances, etc.) tracked per room, distinct from
            | the consumable Materials Inventory. Property Custodian only.
            |--------------------------------------------------------------------------
            */

            [
                'module' => 'Room Inventory of Property',

                'permissions' => [

                    ['name' => 'Manage Property Inventory', 'slug' => 'manage-property-inventory'],
                    ['name' => 'Manage Property Issuance', 'slug' => 'manage-property-issuance'],

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
                    ['name' => 'Reset User Passwords', 'slug' => 'reset-user-passwords'],

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