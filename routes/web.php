<?php

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\JobRequestController;
use App\Http\Controllers\UtilityScheduleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Supervisor\MaterialController;
use App\Http\Controllers\Supervisor\WalkinRequestController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\Supervisor\CategoryController;
use App\Http\Controllers\Supervisor\UnitController;
use App\Http\Controllers\Supervisor\DepartmentController;
use App\Http\Controllers\Supervisor\InventoryMovementController;
use App\Http\Controllers\Supervisor\DepartmentInventoryController;
use App\Http\Controllers\Supervisor\ReportController;
use App\Http\Controllers\Supervisor\Procurement\ProcurementDashboardController;
use App\Http\Controllers\Supervisor\Procurement\ProcurementPlanController;
use App\Http\Controllers\Supervisor\Procurement\ProcurementPlanItemController;
use App\Http\Controllers\Supervisor\Procurement\ProcurementReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeContactController;
use App\Http\Controllers\EmployeeEducationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SystemSettingsController;





Route::get(
    '/materials/import',
    [MaterialController::class, 'importForm']
)->name('materials.import.form');

Route::post(
    '/materials/import',
    [MaterialController::class, 'importStore']
)->name('materials.import.store');

Route::middleware(['auth'])->group(function () {

    Route::get('/material-request', [MaterialRequestController::class, 'create']);

    Route::get('/material-request/history', [MaterialRequestController::class, 'history'])
    ->name('material-request.history');

    // 🖨 PRINT SLIP
    Route::get(
        '/material-request/{id}/slip',
        [MaterialRequestController::class, 'slip']
    )->name('material-request.slip');

    Route::post('/material-request', [MaterialRequestController::class, 'store']);

});

/*
|--------------------------------------------------------------------------
| JOB REQUESTS (PPLS-QF-02) — any logged-in employee can submit one;
| approval and personnel assignment are permission-gated further below.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/job-requests/create', [JobRequestController::class, 'create'])
        ->name('job-requests.create');

    Route::post('/job-requests', [JobRequestController::class, 'store'])
        ->name('job-requests.store');

    Route::get('/job-requests/history', [JobRequestController::class, 'history'])
        ->name('job-requests.history');

    Route::get('/job-requests/my-assigned', [JobRequestController::class, 'myAssignedJobs'])
        ->name('job-requests.my-assigned');

    // Authorization is done inside the controller (must be one of the
    // assigned personnel) rather than a permission, since the workers
    // themselves (e.g. Rony) don't hold assign-job-request-personnel.
    Route::post('/job-requests/{id}/mark-work-done', [JobRequestController::class, 'markWorkDone'])
        ->name('job-requests.mark-work-done');

});

Route::middleware(['auth', 'permission:approve-job-requests-physical-plant,approve-job-requests-utility'])->group(function () {

    Route::get('/job-requests', [JobRequestController::class, 'index'])
        ->name('job-requests.index');

    // KEEP BEFORE job-requests.show — {id} below would otherwise swallow
    // this static path as a route-model-binding lookup.
    Route::get('/job-requests/reports', [JobRequestController::class, 'report'])
        ->name('job-requests.reports');

    Route::get('/job-requests/reports/print', [JobRequestController::class, 'reportPrint'])
        ->name('job-requests.reports.print');

    Route::post('/job-requests/{id}/approve', [JobRequestController::class, 'approve'])
        ->name('job-requests.approve');

    Route::post('/job-requests/{id}/reject', [JobRequestController::class, 'reject'])
        ->name('job-requests.reject');

});

Route::middleware(['auth', 'permission:assign-job-request-personnel'])->group(function () {

    Route::get('/job-requests/{id}/assign', [JobRequestController::class, 'assignForm'])
        ->name('job-requests.assign');

    Route::post('/job-requests/{id}/assign', [JobRequestController::class, 'assignStore'])
        ->name('job-requests.assign.store');

    Route::post('/job-requests/{id}/complete', [JobRequestController::class, 'markCompleted'])
        ->name('job-requests.complete');

});

// KEEP LAST — {id} below would otherwise swallow any static job-requests
// path (e.g. /job-requests/reports) registered after it as a
// route-model-binding lookup.
Route::middleware(['auth'])->group(function () {

    Route::get('/job-requests/{id}', [JobRequestController::class, 'show'])
        ->name('job-requests.show');

    Route::get('/job-requests/{id}/print', [JobRequestController::class, 'print'])
        ->name('job-requests.print');

});

/*
|--------------------------------------------------------------------------
| UTILITY SCHEDULING — Mark (General Services Officer) builds the duty
| roster; workers (e.g. Rony, Aldrin) only see their own schedule.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'permission:manage-utility-schedule'])->group(function () {

    Route::get('/utility-schedule', [UtilityScheduleController::class, 'index'])
        ->name('utility-schedule.index');

    Route::get('/utility-schedule/print', [UtilityScheduleController::class, 'print'])
        ->name('utility-schedule.print');

    Route::post('/utility-schedule', [UtilityScheduleController::class, 'store'])
        ->name('utility-schedule.store');

    Route::put('/utility-schedule/{id}', [UtilityScheduleController::class, 'update'])
        ->name('utility-schedule.update');

    Route::delete('/utility-schedule/{id}', [UtilityScheduleController::class, 'destroy'])
        ->name('utility-schedule.destroy');

    Route::post('/utility-schedule/duplicate-week', [UtilityScheduleController::class, 'duplicateWeek'])
        ->name('utility-schedule.duplicate-week');

    Route::get('/utility-schedule/attendance-report', [UtilityScheduleController::class, 'attendanceReport'])
        ->name('utility-schedule.attendance-report');

    Route::get('/utility-schedule/attendance-report/print', [UtilityScheduleController::class, 'attendanceReportPrint'])
        ->name('utility-schedule.attendance-report.print');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/utility-schedule/my', [UtilityScheduleController::class, 'mySchedule'])
        ->name('utility-schedule.my');

    // Authorization is done inside the controller (must be the assigned
    // worker), same pattern as Job Request's mark-work-done route.
    Route::post('/utility-schedule/{id}/check-in', [UtilityScheduleController::class, 'checkIn'])
        ->name('utility-schedule.check-in');

    Route::post('/utility-schedule/{id}/check-out', [UtilityScheduleController::class, 'checkOut'])
        ->name('utility-schedule.check-out');

});

// ── Landing page ──────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ── Guest routes ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login',     [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',    [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// ── Logout ────────────────────────────────────────────────
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ── Forced password change (accounts created with a system-generated
// password, e.g. Quick Add Employee, must set their own before continuing) ──
Route::middleware('auth')->group(function () {

    Route::get('/force-password-change', [ForcePasswordChangeController::class, 'edit'])
        ->name('password.force.edit');

    Route::put('/force-password-change', [ForcePasswordChangeController::class, 'update'])
        ->name('password.force.update');

});

    Route::get('/supervisor/dashboard', function () {
        return view('supervisor.dashboard', [
            'pendingCount'  => \App\Models\User::where('status', 'pending')->count(),
            'approvedCount' => \App\Models\User::where('status', 'approved')->count(),
            'rejectedCount' => \App\Models\User::where('status', 'rejected')->count(),
            'pendingUsers'  => \App\Models\User::where('status', 'pending')
                                    ->where('role', 'personnel')->latest()->get(),
        
            
            // ⚠ LOW STOCK
            'lowStockMaterials' =>
                \App\Models\Material::whereColumn(
                    'quantity',
                    '<=',
                    'threshold'
                )
                ->where('quantity', '>', 5)
                ->get(),


                // 📊 MOST REQUESTED MATERIALS
            'mostRequestedMaterials' =>

                \App\Models\MaterialRequestItem::select(
                    'material_id',
                    \DB::raw('SUM(quantity) as total_requested')
                )
                ->with('material')
                ->groupBy('material_id')
                ->orderByDesc('total_requested')
                ->take(5)
                ->get(),

                // 📈 MONTHLY MATERIAL USAGE
            'monthlyUsage' =>

                \App\Models\MaterialRequestItem::select(
                    DB::raw("DATE_FORMAT(created_at, '%M %Y') as month"),
                    DB::raw('SUM(quantity) as total_used')
                )
                ->groupBy('month')
                ->orderByRaw('MIN(created_at) DESC')
                ->take(6)
                ->get(),

                // 🚨 CRITICAL STOCKS
            'criticalStocks' =>

                \App\Models\Material::where('quantity', '<=', 5)
                    ->where('quantity', '>', 0)
                    ->orderBy('quantity', 'asc')
                    ->get(),

            // ❌ OUT OF STOCK
            'outOfStocks' =>

                \App\Models\Material::where('quantity', '<=', 0)
                    ->get(),     
                
       ]);
    })->middleware(['auth', 'role:supervisor'])
    ->name('supervisor.dashboard');

// ── Authenticated routes ──────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Shared dashboard redirect
    Route::get('/dashboard', function () {
        return auth()->user()->isSupervisor()
            ? redirect()->route('supervisor.dashboard')
            : redirect()->route('personnel.dashboard');
    })->name('dashboard');

    // ── Notifications ──
    Route::get('/notifications',          [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/fetch',    [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all',  [NotificationController::class, 'markAllRead'])->name('notifications.readAll');


    // ── Supervisor / Admin routes ──

    /*
    |--------------------------------------------------------------------------
    | ROLE PERMISSIONS
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:manage-roles')->group(function () {

        Route::get(
            '/roles/{role}/permissions',
            [RolePermissionController::class, 'index']
        )->name('roles.permissions');

        Route::post(
            '/roles/{role}/permissions',
            [RolePermissionController::class, 'update']
        )->name('roles.permissions.update');

    });

    /*
    |--------------------------------------------------------------------------
    | SYSTEM ADMINISTRATION
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-activity-logs')->group(function () {

        Route::get(
            '/admin/activity-logs',
            [ActivityLogController::class, 'index']
        )->name('admin.activity-logs.index');

    });

    Route::middleware('permission:manage-system-settings')->group(function () {

        Route::get(
            '/admin/system-settings',
            [SystemSettingsController::class, 'index']
        )->name('admin.system-settings.index');

        Route::post(
            '/admin/system-settings/maintenance-mode',
            [SystemSettingsController::class, 'updateMaintenanceMode']
        )->name('admin.system-settings.maintenance-mode');

        Route::post(
            '/admin/system-settings/email',
            [SystemSettingsController::class, 'updateEmailSettings']
        )->name('admin.system-settings.email');

        Route::post(
            '/admin/system-settings/email/test',
            [SystemSettingsController::class, 'sendTestEmail']
        )->name('admin.system-settings.email.test');

    });

    /*
    |--------------------------------------------------------------------------
    | USER ACCESS MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-user-access')->group(function () {

        Route::get(
            '/admin/user-access',
            [UserAccessController::class, 'index']
        )->name('admin.user-access.index');

        Route::get(
            '/admin/user-access/{user}',
            [UserAccessController::class, 'show']
        )->name('admin.user-access.show');

    });

    Route::middleware('permission:assign-roles')->group(function () {

        Route::get(
            '/admin/user-access/{user}/edit',
            [UserAccessController::class, 'edit']
        )->name('admin.user-access.edit');

        Route::put(
            '/admin/user-access/{user}',
            [UserAccessController::class, 'update']
        )->name('admin.user-access.update');

    });

    Route::middleware('permission:manage-user-status')->group(function () {

        Route::patch(
            '/admin/user-access/{user}/status',
            [UserAccessController::class, 'updateStatus']
        )->name('admin.user-access.update-status');

    });

    Route::middleware('permission:reset-user-passwords')->group(function () {

        Route::get(
            '/admin/reset-password',
            [UserAccessController::class, 'resetPasswordIndex']
        )->name('admin.reset-password.index');

        Route::post(
            '/admin/reset-password/{user}',
            [UserAccessController::class, 'resetPassword']
        )->name('admin.reset-password.store');

    });

    // Password-reset audit trail — super admin account only, regardless of
    // permissions, so admin-level resets stay visible to nobody but root.
    Route::middleware('role:supervisor')->group(function () {

        Route::get(
            '/admin/reset-password/logs',
            [UserAccessController::class, 'resetPasswordLogs']
        )->name('admin.reset-password.logs');

    });

    Route::middleware('permission:manage-permissions')->group(function () {

    Route::resource('permissions', PermissionController::class)
        ->only(['index', 'edit', 'update']);

});

        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT PLANNING
        |--------------------------------------------------------------------------
        */
        

        Route::prefix('procurement')
            ->name('procurement.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Dashboard
                |--------------------------------------------------------------------------
                */

                Route::middleware('permission:view-ppmp')->group(function () {

                    Route::get(
                        '/dashboard',
                        [ProcurementDashboardController::class, 'index']
                    )->name('dashboard');

                });

                /*
                |--------------------------------------------------------------------------
                | Annual PPMP
                |--------------------------------------------------------------------------
                | index/show are also reachable by a department-scoped user
                | (manage-own-department-ppmp-items); create/edit/update/destroy
                | remain full-access only (view-ppmp).
                */

                Route::resource(
                    'plans',
                    ProcurementPlanController::class
                )
                    ->middlewareFor(
                        ['index', 'show'],
                        'permission:view-ppmp,manage-own-department-ppmp-items'
                    )
                    ->middlewareFor(
                        ['create', 'store', 'edit', 'update', 'destroy'],
                        'permission:view-ppmp'
                    );

                Route::middleware('permission:view-ppmp')->group(function () {

                    Route::post(
                        'plans/{plan}/submit',
                        [ProcurementPlanController::class, 'submit']
                    )->middleware('permission:submit-ppmp')->name('plans.submit');

                    Route::post(
                        'plans/{plan}/approve',
                        [ProcurementPlanController::class, 'approve']
                    )->middleware('permission:approve-ppmp')->name('plans.approve');

                    Route::post(
                        'plans/{plan}/reject',
                        [ProcurementPlanController::class, 'reject']
                    )->middleware('permission:reject-ppmp')->name('plans.reject');

                    Route::post(
                        'plans/items/{item}/toggle-approval',
                        [ProcurementPlanItemController::class, 'toggleApproval']
                    )->name('plans.items.toggle-approval');

                });

                /*
                |--------------------------------------------------------------------------
                | MATERIAL DETAILS (AJAX) + PROCUREMENT PLAN ITEMS
                |--------------------------------------------------------------------------
                | Reachable by full-access roles AND a department-scoped user managing
                | their own department's PPMP items.
                */

                Route::middleware('permission:view-ppmp,manage-own-department-ppmp-items')->group(function () {

                    Route::get(
                        'materials/{material}/details',
                        [MaterialController::class, 'details']
                    )->name('materials.details');

                    Route::post(
                        'plans/{plan}/materials/quick-create',
                        [MaterialController::class, 'quickStoreForProcurement']
                    )->name('materials.quick-create');

                    Route::get(
                        'plans/{plan}/items/create',
                        [ProcurementPlanItemController::class, 'create']
                    )->name('plans.items.create');

                    Route::post(
                        'plans/{plan}/items',
                        [ProcurementPlanItemController::class, 'store']
                    )->name('plans.items.store');

                    Route::get(
                        'plans/items/{item}',
                        [ProcurementPlanItemController::class, 'show']
                    )->name('plans.items.show');

                    Route::put(
                        'plans/items/{item}',
                        [ProcurementPlanItemController::class, 'update']
                    )->name('plans.items.update');

                    Route::delete(
                        'plans/items/{item}',
                        [ProcurementPlanItemController::class, 'destroy']
                    )->name('plans.items.destroy');

                });

            });

        Route::prefix('procurement')
            ->name('procurement.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Budget Monitoring
                |--------------------------------------------------------------------------
                */

                Route::middleware('permission:view-budget-monitoring')->group(function () {

                    Route::get(
                        '/budget-monitoring',
                        [ProcurementReportController::class, 'budgetMonitoring']
                    )->name('budget-monitoring');

                    Route::get(
                        '/budget-monitoring/print',
                        [ProcurementReportController::class, 'budgetMonitoringPrint']
                    )->name('budget-monitoring.print');

                });

                /*
                |--------------------------------------------------------------------------
                | Purchase Forecast
                |--------------------------------------------------------------------------
                */

                Route::middleware('permission:view-purchase-forecast')->group(function () {

                    Route::get(
                        '/purchase-forecast',
                        [ProcurementReportController::class, 'purchaseForecast']
                    )->name('purchase-forecast');

                    Route::get(
                        '/purchase-forecast/print',
                        [ProcurementReportController::class, 'purchaseForecastPrint']
                    )->name('purchase-forecast.print');

                });

                /*
                |--------------------------------------------------------------------------
                | Procurement Calendar
                |--------------------------------------------------------------------------
                */

                Route::middleware('permission:view-procurement-calendar')->group(function () {

                    Route::get(
                        '/calendar',
                        [ProcurementReportController::class, 'calendar']
                    )->name('calendar');

                    Route::get(
                        '/calendar/print',
                        [ProcurementReportController::class, 'calendarPrint']
                    )->name('calendar.print');

                });

            });

/*
    |--------------------------------------------------------------------------
    | INVENTORY
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-department-inventory')->group(function () {

        Route::get(
            '/department-inventory',
            [\App\Http\Controllers\Supervisor\DepartmentInventoryController::class, 'index']
        )->name('department.inventory');

        Route::get(
            '/department-inventory-balance',
            [DepartmentInventoryController::class, 'balance']
        )->name('department.inventory.balance');

        Route::get(
            '/department-inventory-balance/{department}',
            [DepartmentInventoryController::class, 'details']
        )->name('department.inventory.details');

    });

    /*
    |--------------------------------------------------------------------------
    | INVENTORY REPORTS
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-reports')->group(function () {

        Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

        Route::get('/reports/inventory', [ReportController::class, 'inventory'])
        ->name('reports.inventory');

        Route::get(
            '/inventory/reports/summary',
            [MaterialController::class, 'inventorySummary']
        )->name('inventory.summary');

        Route::get(
            '/inventory/reports/executive-summary',
            [MaterialController::class, 'executiveSummary']
        )->name('inventory.executive');

        /*
        |--------------------------------------------------------------------------
        | INDIVIDUAL INVENTORY REPORTS
        |--------------------------------------------------------------------------
        */

        Route::get('/inventory/reports/critical-stock', [MaterialController::class, 'criticalReport'])
        ->name('inventory.critical');

        Route::get(
            '/inventory/reports/low-stock',
            [MaterialController::class, 'lowStockReport']
        )->name('inventory.low');

        Route::get(
            '/inventory/reports/out-of-stock',
            [MaterialController::class, 'outOfStockReport']
        )->name('inventory.out');

        Route::get(
            '/inventory/reports/expiration',
            [MaterialController::class, 'expirationReport']
        )->name('inventory.expiration');

        Route::get(
            '/inventory/reports/department-summary',
            [MaterialController::class, 'departmentSummaryReport'
        ])->name('inventory.department');

        Route::get(
            '/inventory/reports/purchase-recommendations',
            [MaterialController::class, 'purchaseRecommendations']
        )->name('inventory.purchase-recommendations');

        Route::get(
            '/inventory/reports/frequently-requested',
            [MaterialController::class, 'frequentlyRequestedReport']
        )->name('inventory.frequently-requested');

        Route::get(
            '/inventory/reports/non-movable',
            [MaterialController::class, 'nonMovableReport']
        )->name('inventory.non-movable');

    });

    Route::middleware('permission:print-reports')->group(function () {

        Route::get(
            '/inventory/reports/summary/print',
            [MaterialController::class, 'inventorySummaryPrint']
        )->name('inventory.summary.print');

        Route::get(
            '/inventory/reports/executive-summary/print',
            [MaterialController::class, 'executiveSummaryPrint']
        )->name('inventory.executive.print');

        Route::get('/inventory/reports/critical-stock/print', [MaterialController::class, 'criticalReportPrint'])
            ->name('inventory.critical.print');

        Route::get('/inventory/reports/low-stock/print', [MaterialController::class, 'lowStockReportPrint'])
            ->name('inventory.low.print');

        Route::get('/inventory/reports/out-of-stock/print', [MaterialController::class, 'outOfStockReportPrint'])
            ->name('inventory.out.print');

        Route::get('/inventory/reports/expiration/print', [MaterialController::class, 'expirationReportPrint'])
            ->name('inventory.expiration.print');

        Route::get('/inventory/reports/department-summary/print', [MaterialController::class, 'departmentSummaryReportPrint'])
            ->name('inventory.department.print');

        Route::get('/inventory/reports/purchase-recommendations/print', [MaterialController::class, 'purchaseRecommendationsPrint'])
            ->name('inventory.purchase-recommendations.print');

        Route::get('/inventory/reports/frequently-requested/print', [MaterialController::class, 'frequentlyRequestedReportPrint'])
            ->name('inventory.frequently-requested.print');

        Route::get('/inventory/reports/non-movable/print', [MaterialController::class, 'nonMovableReportPrint'])
            ->name('inventory.non-movable.print');

    });


    /*
    |--------------------------------------------------------------------------
    | Employee Educational Background
    |--------------------------------------------------------------------------
    */

   Route::middleware('permission:edit-employee-profile')->group(function () {

        Route::get(
            '/employees/{employee}/education/create',
            [EmployeeEducationController::class, 'create']
        )->name('employees.education.create');

        Route::post(
            '/employees/{employee}/education',
            [EmployeeEducationController::class, 'store']
        )->name('employees.education.store');

        Route::get(
            '/employees/{employee}/education/{education}/edit',
            [EmployeeEducationController::class, 'edit']
        )->name('employees.education.edit');

        Route::put(
            '/employees/{employee}/education/{education}',
            [EmployeeEducationController::class, 'update']
        )->name('employees.education.update');

        Route::delete(
            '/employees/{employee}/education/{education}',
            [EmployeeEducationController::class, 'destroy']
        )->name('employees.education.destroy');

    });
    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE MASTER
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-employees')->group(function () {

        Route::resource('employees', EmployeeController::class)
            ->only([
                'index',
            ]);

    });

    // KEEP BEFORE employees.show — {employee} below would otherwise swallow
    // this static path as a route-model-binding lookup.
    Route::middleware('permission:view-utility-staff')->group(function () {

        Route::get('/employees/utility-staff', [EmployeeController::class, 'utilityStaff'])
            ->name('employees.utility-staff');

    });

    // Individual profiles are also reachable by scoped supervisors (e.g. the
    // General Services Officer's utility/maintenance staff list) who hold
    // view-employee-profile without the full Employee Master permission.
    Route::middleware('permission:view-employees,view-employee-profile')->group(function () {

        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])
            ->name('employees.show');

    });

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE PROFILE
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:edit-employee-profile')->group(function () {

        Route::get(
            '/employees/{employee}/profile/create',
            [EmployeeProfileController::class, 'create']
        )->name('employees.profile.create');

        Route::post(
            '/employees/{employee}/profile',
            [EmployeeProfileController::class, 'store']
        )->name('employees.profile.store');

        Route::get(
            '/employees/{employee}/profile/edit',
            [EmployeeProfileController::class, 'edit']
        )->name('employees.profile.edit');

        Route::put(
            '/employees/{employee}/profile',
            [EmployeeProfileController::class, 'update']
        )->name('employees.profile.update');

    });

    Route::middleware('permission:manage-roles')->group(function () {

    Route::resource('roles', RoleController::class);

    Route::patch('/roles/{role}/toggle-status',
        [RoleController::class, 'toggleStatus'])
        ->name('roles.toggle-status');

});

    /*
    |--------------------------------------------------------------------------
    | Employee Contact Information
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:edit-employee-profile')->group(function () {

        Route::get('/employees/{employee}/contact/create',
            [EmployeeContactController::class, 'create'])
            ->name('employees.contact.create');

        Route::post('/employees/{employee}/contact',
            [EmployeeContactController::class, 'store'])
            ->name('employees.contact.store');

        Route::get('/employees/{employee}/contact/edit',
            [EmployeeContactController::class, 'edit'])
            ->name('employees.contact.edit');

        Route::put('/employees/{employee}/contact',
            [EmployeeContactController::class, 'update'])
            ->name('employees.contact.update');

    });


    
    /*
    |--------------------------------------------------------------------------
    | WALK-IN MATERIAL ISSUANCE
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-walkin-requests')->group(function () {

        Route::get(
            '/walkin-requests',
            [WalkinRequestController::class, 'index']
        )->name('walkin.index');

        Route::get(
            '/walkin-requests/history',
            [WalkinRequestController::class, 'history']
        )->name('walkin.history');

        Route::get(
            '/walkin-requests/employee/{personnel}',
            [WalkinRequestController::class, 'employeeHistory']
        )->name('walkin.employee-history');

    });

    Route::middleware('permission:create-walkin-requests')->group(function () {

        Route::get(
            '/walkin-requests/create',
            [WalkinRequestController::class, 'create']
        )->name('walkin.create');

        Route::post(
            '/walkin-requests/store',
            [WalkinRequestController::class, 'store']
        )->name('walkin.store');

        Route::post(
            '/walkin-requests/quick-add-employee',
            [WalkinRequestController::class, 'quickAddEmployee']
        )->name('walkin.quick-add-employee');

        Route::get(
            '/walkin-requests/generate-employee-id/{employmentType}',
            [WalkinRequestController::class, 'getEmployeeId']
        )->name('walkin.generate-employee-id');

        Route::get(
            '/walkin-requests/employment-type/{employmentType}/positions',
            [WalkinRequestController::class, 'getPositions']
        )->name('walkin.positions');

    });

    Route::middleware('permission:view-walkin-requests')->group(function () {

        /* KEEP THESE LAST — {id} must not shadow /create or /store above */
        Route::get(
            '/walkin-requests/{id}',
            [WalkinRequestController::class, 'show']
        )->name('walkin.show');

        Route::get(
            '/walkin-requests/{id}/print',
            [WalkinRequestController::class, 'print']
        )->name('walkin.print');

    });

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENTS
    |--------------------------------------------------------------------------
    */

    Route::prefix('supervisor')
        ->name('supervisor.')
        ->group(function () {

            Route::middleware('permission:view-departments')->group(function () {
                Route::resource('departments', DepartmentController::class)->only(['index']);
            });

            Route::middleware('permission:create-departments')->group(function () {
                Route::resource('departments', DepartmentController::class)->only(['create', 'store']);
            });

            Route::middleware('permission:edit-departments')->group(function () {
                Route::resource('departments', DepartmentController::class)->only(['edit', 'update']);
            });

            Route::middleware('permission:delete-departments')->group(function () {
                Route::resource('departments', DepartmentController::class)->only(['destroy']);
            });

    });
    /*
    |--------------------------------------------------------------------------
    | INVENTORY MOVEMENTS
    |--------------------------------------------------------------------------
    */

    Route::prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {

        Route::middleware('permission:view-inventory-movements')->group(function () {

            Route::get('/inventory-movements',
                [InventoryMovementController::class, 'index'])
                ->name('inventory.movements.index');

        });

    });


    // 📦 Material Requests
    Route::middleware('permission:process-material-requests')->group(function () {

        Route::get('/supervisor/material-requests', [MaterialRequestController::class, 'index']);

        Route::post('/supervisor/material-requests/{id}/approve', [MaterialRequestController::class, 'approve']);

        Route::post('/supervisor/material-requests/{id}/reject', [MaterialRequestController::class, 'reject']);

        Route::post(
            '/supervisor/material-requests/{id}/release',
            [MaterialRequestController::class, 'release']
        );

    });

    // ✅ Units
    Route::middleware('permission:view-units')->group(function () {
        Route::get('/units', [UnitController::class, 'index'])
            ->name('units.index');
    });

    Route::middleware('permission:create-units')->group(function () {

        Route::get('/units/create', [UnitController::class, 'create'])
            ->name('units.create');

        Route::post('/units/store', [UnitController::class, 'store'])
            ->name('units.store');

    });

    Route::middleware('permission:edit-units')->group(function () {

        Route::get('/units/{id}/edit', [UnitController::class, 'edit'])
            ->name('units.edit');

        Route::put('/units/{id}', [UnitController::class, 'update'])
            ->name('units.update');

    });

    Route::middleware('permission:delete-units')->group(function () {
        Route::delete('/units/{id}', [UnitController::class, 'destroy'])
            ->name('units.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-categories')->group(function () {

        Route::get(
            '/categories',
            [CategoryController::class, 'index']
        )->name('categories.index');

    });

    Route::middleware('permission:create-categories')->group(function () {

        Route::get(
            '/categories/create',
            [CategoryController::class, 'create']
        )->name('categories.create');

        Route::post(
            '/categories/store',
            [CategoryController::class, 'store']
        )->name('categories.store');

    });

    Route::middleware('permission:edit-categories')->group(function () {

        Route::get(
            '/categories/{id}/edit',
            [CategoryController::class, 'edit']
        )->name('categories.edit');

        Route::put(
            '/categories/{id}',
            [CategoryController::class, 'update']
        )->name('categories.update');

    });

    Route::middleware('permission:delete-categories')->group(function () {

        Route::delete(
            '/categories/{id}',
            [CategoryController::class, 'destroy']
        )->name('categories.destroy');

    });

    
    /*
    |--------------------------------------------------------------------------
    | MATERIALS
    |--------------------------------------------------------------------------
    */

    // Material Logs FIRST
    Route::middleware('permission:view-materials')->group(function () {

        Route::get('/materials', [MaterialController::class, 'index'])
            ->name('materials.index');
    });

    // View Materials
    Route::middleware('permission:create-materials')->group(function () {

        Route::get('/materials/create', [MaterialController::class, 'create'])
            ->name('materials.create');

        Route::post('/materials/store', [MaterialController::class, 'store'])
            ->name('materials.store');

    });

    Route::middleware('permission:edit-materials')->group(function () {

        Route::get('/materials/{id}/edit', [MaterialController::class, 'edit'])
            ->name('materials.edit');

        Route::put('/materials/{id}', [MaterialController::class, 'update'])
            ->name('materials.update');

        Route::post('/materials/bulk-assign-department', [MaterialController::class, 'bulkAssignDepartment'])
            ->name('materials.bulk-assign-department');

        Route::post('/materials/bulk-assign-classification', [MaterialController::class, 'bulkAssignClassification'])
            ->name('materials.bulk-assign-classification');

    });

    Route::middleware('permission:delete-materials')->group(function () {

        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])
            ->name('materials.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | Material Logs
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-material-logs')->group(function () {

        // Material Logs
        Route::get('/materials/logs', [MaterialController::class, 'logs'])
            ->name('materials.logs');

        // Material Details
        Route::get('/materials/{id}', [MaterialController::class, 'show'])
            ->name('materials.show');

    });

    // ✅ Restock Materials

    Route::middleware('permission:restock-materials')->group(function () {

        Route::get('/materials/{id}/restock', [MaterialController::class, 'restockForm'])
            ->name('materials.restock.form');

        Route::post('/materials/{id}/restock', [MaterialController::class, 'restock'])
            ->name('materials.restock');

    });


    // User approval
    Route::middleware('permission:approve-users')->group(function () {

        Route::get('admin/users/pending', [UserApprovalController::class, 'index'])
            ->name('admin.users.pending');

        Route::post('admin/users/{id}/approve', [UserApprovalController::class, 'approve'])
            ->name('admin.users.approve');

    });

    Route::middleware('permission:reject-users')->group(function () {

        Route::post('admin/users/{id}/reject', [UserApprovalController::class, 'reject'])
            ->name('admin.users.reject');

    });

    Route::middleware('permission:onboard-users')->group(function () {

        // ✅ Employee Onboarding Page
        Route::get('admin/users/{user}/onboarding',
            [UserApprovalController::class, 'onboarding']
        )->name('admin.users.onboarding');

        Route::post('admin/users/{user}/complete-onboarding',
            [UserApprovalController::class, 'completeOnboarding']
        )->name('admin.users.complete-onboarding');

        Route::get('/admin/users/generate-employee-id/{employmentType}',
        [UserApprovalController::class, 'getEmployeeId']
        )->name('admin.users.generate-employee-id');

        Route::get(
            '/admin/users/employment-type/{employmentType}/positions',
            [UserApprovalController::class, 'getPositions']
        )->name('admin.users.positions');

    });

    // Leave admin
    Route::middleware('role:supervisor')->group(function () {

        Route::get('leave-requests',       [LeaveController::class, 'adminIndex'])->name('leave.requests');
        Route::get('leave/admin',          [LeaveController::class, 'adminIndex']);
        Route::post('leave/approve/{id}',  [LeaveController::class, 'approve']);
        Route::post('leave/reject/{id}',   [LeaveController::class, 'reject']);

    });
          
    // ── Personnel routes ──
    Route::middleware('role:personnel')->group(function () {
        Route::get('/personnel/dashboard', [PersonnelController::class, 'dashboard'])->name('personnel.dashboard');
        Route::get('/personnel/create',    [PersonnelController::class, 'create']);
        Route::post('/personnel/store',    [PersonnelController::class, 'store']);

        // Leave
        Route::get('leave',          [LeaveController::class, 'index'])->name('leave.index');
        Route::post('leave',         [LeaveController::class, 'store']);
        Route::get('leave/history',  [LeaveController::class, 'history'])->name('leave.history');
    });

});

Route::get('/zip-test', function () {

    return [
        'php_binary' => PHP_BINARY,
        'php_version' => PHP_VERSION,
        'zip_loaded' => extension_loaded('zip'),
        'zip_class' => class_exists('ZipArchive'),
        'loaded_ini' => php_ini_loaded_file(),
    ];

});

// 🔧 TEMP FIX ROUTE (REMOVE AFTER USE)
Route::get('/fix-personnel-link', function () {
    $personnels = \App\Models\Personnel::all();

    foreach ($personnels as $p) {
        $user = \App\Models\User::where('name', $p->fullname)->first();

        if ($user) {
            $p->user_id = $user->id;
            $p->save();
        }
    }

    return "Done fixing!";
});