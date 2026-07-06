<?php

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PersonnelController;
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


Route::get('/zip-test', function () {

    return [
        'php_version' => PHP_VERSION,
        'zip_loaded' => extension_loaded('zip'),
        'zip_class' => class_exists('ZipArchive'),
        'loaded_ini' => php_ini_loaded_file(),
    ];

});

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

// ── Landing page ──────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ── Guest routes ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login',     [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',    [AuthenticatedSessionController::class, 'store']);
});

// ── Logout ────────────────────────────────────────────────
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

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
Route::middleware('role:supervisor')->group(function () {

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

            Route::get(
                '/dashboard',
                [ProcurementDashboardController::class, 'index']
            )->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Annual PPMP
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'plans',
                ProcurementPlanController::class
            );

            /*
            |--------------------------------------------------------------------------
            | MATERIAL DETAILS (AJAX)
            |--------------------------------------------------------------------------
            */

            Route::get(
                'materials/{material}/details',
                [MaterialController::class, 'details']
            )->name('materials.details');

            /*
            |--------------------------------------------------------------------------
            | PROCUREMENT PLAN ITEMS
            |--------------------------------------------------------------------------
            */

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

    Route::get(
        '/department-inventory',
        [\App\Http\Controllers\Supervisor\DepartmentInventoryController::class, 'index']
    )->name('department.inventory');
    
    Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

    Route::get('/reports/inventory', [ReportController::class, 'inventory'])
    ->name('reports.inventory');

    Route::get(
        '/department-inventory-balance',
        [DepartmentInventoryController::class, 'balance']
    )->name('department.inventory.balance');
    
    Route::get(
        '/department-inventory-balance/{department}',
        [DepartmentInventoryController::class, 'details']
    )->name('department.inventory.details');

    /*
    |--------------------------------------------------------------------------
    | INVENTORY REPORTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/inventory/reports/summary',
        [MaterialController::class, 'inventorySummary']
    )->name('inventory.summary');

    Route::get(
        '/inventory/reports/summary/print',
        [MaterialController::class, 'inventorySummaryPrint']
    )->name('inventory.summary.print');

    Route::get(
        '/inventory/reports/executive-summary',
        [MaterialController::class, 'executiveSummary']
    )->name('inventory.executive');

    Route::get(
        '/inventory/reports/executive-summary/print',
        [MaterialController::class, 'executiveSummaryPrint']
    )->name('inventory.executive.print');

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL INVENTORY REPORTS
    |--------------------------------------------------------------------------
    */

    Route::get('/inventory/reports/critical-stock', [MaterialController::class, 'criticalReport'])
    ->name('inventory.critical');

    Route::get('/inventory/reports/critical-stock/print', [MaterialController::class, 'criticalReportPrint'])
        ->name('inventory.critical.print');

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

    /*
    |--------------------------------------------------------------------------
    | WALK-IN MATERIAL ISSUANCE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/walkin-requests',
        [WalkinRequestController::class, 'index']
    )->name('walkin.index');

    Route::get(
        '/walkin-requests/create',
        [WalkinRequestController::class, 'create']
    )->name('walkin.create');

    Route::post(
        '/walkin-requests/store',
        [WalkinRequestController::class, 'store']
    )->name('walkin.store');

    Route::get(
        '/walkin-requests/history',
        [WalkinRequestController::class, 'history']
    )->name('walkin.history');

    /* KEEP THESE LAST */
    Route::get(
        '/walkin-requests/{id}',
        [WalkinRequestController::class, 'show']
    )->name('walkin.show');

    Route::get(
        '/walkin-requests/{id}/print',
        [WalkinRequestController::class, 'print']
    )->name('walkin.print');

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENTS
    |--------------------------------------------------------------------------
    */

    Route::prefix('supervisor')
        ->name('supervisor.')
        ->group(function () {

            Route::resource('departments', DepartmentController::class);

    });
    /*
    |--------------------------------------------------------------------------
    | INVENTORY MOVEMENTS
    |--------------------------------------------------------------------------
    */
        
    Route::prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {

        Route::get('/inventory-movements',
            [InventoryMovementController::class, 'index'])
            ->name('inventory.movements.index');

    });
    
   
    // ✅ RESTOCK MATERIAL
    Route::get('/materials/{id}/restock',
        [MaterialController::class, 'restockForm'])
        ->name('materials.restock.form');

    Route::post('/materials/{id}/restock',
        [MaterialController::class, 'restock'])
        ->name('materials.restock');

    // 📦 Material Requests
    Route::get('/supervisor/material-requests', [MaterialRequestController::class, 'index']);

    Route::post('/supervisor/material-requests/{id}/approve', [MaterialRequestController::class, 'approve']);

    Route::post('/supervisor/material-requests/{id}/reject', [MaterialRequestController::class, 'reject']);
    
    Route::post(
        '/supervisor/material-requests/{id}/release',
        [MaterialRequestController::class, 'release']
    );

  
    // ✅ Units
    Route::get('/units', [UnitController::class, 'index'])
        ->name('units.index');

    Route::get('/units/create', [UnitController::class, 'create'])
        ->name('units.create');

    Route::post('/units/store', [UnitController::class, 'store'])
        ->name('units.store');

    Route::get('/units/{id}/edit', [UnitController::class, 'edit'])
        ->name('units.edit');

    Route::put('/units/{id}', [UnitController::class, 'update'])
        ->name('units.update');

    Route::delete('/units/{id}', [UnitController::class, 'destroy'])
        ->name('units.destroy');

    // ✅ Categories
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::get('/categories/create', [CategoryController::class, 'create'])
        ->name('categories.create');

    Route::post('/categories/store', [CategoryController::class, 'store'])
        ->name('categories.store');

    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])
        ->name('categories.edit');

    Route::put('/categories/{id}', [CategoryController::class, 'update'])
        ->name('categories.update');

    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    Route::get('/materials/logs', [MaterialController::class, 'logs'])
    ->name('materials.logs');

    

    // ✅ Inventory (Materials)

    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials/store', [MaterialController::class, 'store'])->name('materials.store');

    Route::get('/materials/{id}',
        [MaterialController::class, 'show'])
        ->name('materials.show');
        
    Route::get('/materials/{id}/edit', [MaterialController::class, 'edit'])
        ->name('materials.edit');

    Route::put('/materials/{id}', [MaterialController::class, 'update'])
        ->name('materials.update');

    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])
    ->name('materials.destroy');

    // ✅ Restock Materials
    Route::get('/materials/{id}/restock', [MaterialController::class, 'restockForm'])
        ->name('materials.restock.form');

    Route::post('/materials/{id}/restock', [MaterialController::class, 'restock'])
        ->name('materials.restock');


    // User approval
    Route::get('admin/users/pending',      [UserApprovalController::class, 'index'])->name('admin.users.pending');
    Route::post('admin/users/{id}/approve',[UserApprovalController::class, 'approve'])->name('admin.users.approve');
    Route::post('admin/users/{id}/reject', [UserApprovalController::class, 'reject'])->name('admin.users.reject');

    // Leave admin
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