<?php

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PersonnelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Supervisor\MaterialController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\Supervisor\CategoryController;
use App\Http\Controllers\Supervisor\UnitController;
use App\Models\Material;


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

    // 📦 Material Requests
    Route::get('/supervisor/material-requests', [MaterialRequestController::class, 'index']);

    Route::post('/supervisor/material-requests/{id}/approve', [MaterialRequestController::class, 'approve']);

    Route::post('/supervisor/material-requests/{id}/reject', [MaterialRequestController::class, 'reject']);
    


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
                )->get(),
       ]);
    })->name('supervisor.dashboard');

    // ✅ Inventory (Materials)
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials/store', [MaterialController::class, 'store'])->name('materials.store');

    Route::get('/materials/{id}/edit', [MaterialController::class, 'edit'])
        ->name('materials.edit');

    Route::put('/materials/{id}', [MaterialController::class, 'update'])
        ->name('materials.update');

    Route::delete('/materials/{id}', [MaterialController::class, 'destroy'])
        ->name('materials.destroy');

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