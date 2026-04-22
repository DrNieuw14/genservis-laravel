<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

        

            // Pending Leave Requests
            $leaveCount = LeaveRequest::where('status', 'Pending')->count();

            // Pending Users (registration approval)
            $userCount = User::where('status', 'pending')->count();

            // Total Notifications
            $totalNotifications = $leaveCount + $userCount;

            $view->with([
                'leaveNotifCount' => $leaveCount,
                'userNotifCount' => $userCount,
                'totalNotifCount' => $totalNotifications
            ]);
        });
    }
}
