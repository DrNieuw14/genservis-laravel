<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Notification;
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

            if (Auth::check()) {

                $notifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', 0)
                    ->latest()
                    ->take(5)
                    ->get();

                $totalNotifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', 0)
                    ->count();
            }

            $view->with([
                'notifications' => $notifications,
                'totalNotifCount' => $totalNotifications
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
