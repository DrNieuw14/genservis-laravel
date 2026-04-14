<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;
use App\Events\NewNotificationEvent;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

use App\Http\Controllers\PersonnelController;

Route::get('/personnel/create', [PersonnelController::class, 'create'])->middleware('auth');
Route::post('/personnel/store', [PersonnelController::class, 'store'])->middleware('auth');

Route::get('/leave/history', [LeaveController::class, 'history'])->middleware('auth');
Route::get('/leave-test', [LeaveController::class, 'submit'])->middleware('auth');
Route::get('/leave/admin', [LeaveController::class, 'adminIndex'])->middleware('auth');
Route::post('/leave/approve/{id}', [LeaveController::class, 'approve'])->middleware('auth');
Route::post('/leave/reject/{id}', [LeaveController::class, 'reject'])->middleware('auth');

Broadcast::routes(['middleware' => ['auth']]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $user = auth()->user();

    // 👨‍💼 SUPERVISOR
    if ($user->role === 'supervisor') {

        $pending = \App\Models\LeaveRequest::where('status', 'Pending')->count();
        $approved = \App\Models\LeaveRequest::where('status', 'Approved')->count();
        $rejected = \App\Models\LeaveRequest::where('status', 'Rejected')->count();

        $recent = \App\Models\LeaveRequest::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('pending', 'approved', 'rejected', 'recent'));
    }

    // 👤 PERSONNEL
    return redirect('/personnel/dashboard');

})->middleware('auth')->name('dashboard');

Route::get('/personnel/dashboard', function () {
    return view('personnel.dashboard');
})->middleware('auth');

Route::get('/profile', function () {
    return "Profile Page";
})->name('profile.edit')->middleware('auth');

require __DIR__.'/auth.php';

Route::get('/leave', function () {
    return view('leave.form');
})->middleware('auth');

Route::post('/leave', [LeaveController::class, 'store'])->middleware('auth');
Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth');
Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->middleware('auth');

Route::get('/leave-requests', [LeaveController::class, 'adminIndex'])
    ->middleware('auth')
    ->name('leave.requests');

Route::get('/test-notif', function () {
    $notif = Notification::create([
        'user_id' => auth()->user()->id,
        'type' => 'info',
        'title' => 'REALTIME 🚀',
        'message' => 'Instant notification working!',
        'is_read' => 0
    ]);

    event(new NewNotificationEvent($notif));

    \Log::info('EVENT FIRED', ['notif_id' => $notif->id]);

    return "Sent!";
})->middleware('auth');

use App\Http\Controllers\Admin\UserApprovalController;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users/pending', [UserApprovalController::class, 'index'])
        ->name('admin.users.pending');

    Route::post('/admin/users/{id}/approve', [UserApprovalController::class, 'approve'])
        ->name('admin.users.approve');

        
});

