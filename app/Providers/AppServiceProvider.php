<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Notification;
use App\Models\Personnel;
use App\Models\JobRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
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

            $notifications = collect();
            $totalNotifications = 0;
            $pendingJobRequestCount = 0;
            $pendingUtilityLeaveCount = 0;
            $myJobRequestsInProgressCount = 0;
            $myAssignedJobsPendingCount = 0;

            if (Auth::check()) {

                $user = Auth::user();

                $notifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', 0)
                    ->latest()
                    ->take(5)
                    ->get();

                $totalNotifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', 0)
                    ->count();

                $jobRequestCategories = [];

                if ($user->hasPermission('approve-job-requests-physical-plant')) {
                    $jobRequestCategories[] = 'physical_plant';
                }

                if ($user->hasPermission('approve-job-requests-utility')) {
                    $jobRequestCategories[] = 'utility';
                }

                if (!empty($jobRequestCategories)) {
                    $pendingJobRequestCount = JobRequest::whereIn('category', $jobRequestCategories)
                        ->where('status', 'pending')
                        ->count();
                }

                if ($user->hasPermission('approve-utility-leave')) {
                    $pendingUtilityLeaveCount = LeaveRequest::whereIn('personnel_id', Personnel::utilityStaff()->pluck('id'))
                        ->where('status', 'Pending')
                        ->count();
                }

                // Informational, not an action queue — how many of MY OWN
                // submitted job requests are still moving (not yet
                // completed or rejected). Matches JobRequestController::
                // history()'s own scoping (by user_id).
                $myJobRequestsInProgressCount = JobRequest::where('user_id', Auth::id())
                    ->whereNotIn('status', ['completed', 'rejected'])
                    ->count();

                // Genuine action-needed queue (red badge) — jobs assigned to
                // ME that I haven't marked done yet. Once marked work_done
                // it's off my plate and awaiting the approver's sign-off, so
                // that status deliberately isn't counted here.
                if ($user->personnel) {
                    $myAssignedJobsPendingCount = JobRequest::whereHas('assignedPersonnel', fn ($q) => $q->where('personnel.id', $user->personnel->id))
                        ->where('status', 'assigned')
                        ->count();
                }
            }

            $view->with([
                'notifications' => $notifications,
                'totalNotifCount' => $totalNotifications,
                'pendingJobRequestCount' => $pendingJobRequestCount,
                'pendingUtilityLeaveCount' => $pendingUtilityLeaveCount,
                'myJobRequestsInProgressCount' => $myJobRequestsInProgressCount,
                'myAssignedJobsPendingCount' => $myAssignedJobsPendingCount,
            ]);
        });

        $this->applyStoredMailSettings();
    }

    /**
     * Let mail settings saved via the System Settings page override the
     * .env-based defaults, without requiring a server restart. Guarded
     * behind Schema::hasTable() since this boots on every request/command,
     * including ones that run before the settings table migration exists.
     */
    protected function applyStoredMailSettings(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $host = Setting::get('mail_host');

        if (!$host) {
            return;
        }

        config([
            'mail.default' => Setting::get('mail_mailer', config('mail.default')),
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => Setting::get('mail_port', config('mail.mailers.smtp.port')),
            'mail.mailers.smtp.username' => Setting::get('mail_username'),
            'mail.mailers.smtp.password' => Setting::get('mail_password')
                ? decrypt(Setting::get('mail_password'))
                : null,
            'mail.mailers.smtp.encryption' => Setting::get('mail_encryption') ?: null,
            'mail.from.address' => Setting::get('mail_from_address', config('mail.from.address')),
            'mail.from.name' => Setting::get('mail_from_name', config('mail.from.name')),
        ]);
    }
}
