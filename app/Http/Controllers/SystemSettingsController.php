<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'maintenance_mode' => (bool) Setting::get('maintenance_mode', false),
            'mail_mailer' => Setting::get('mail_mailer', config('mail.default')),
            'mail_host' => Setting::get('mail_host', config('mail.mailers.smtp.host')),
            'mail_port' => Setting::get('mail_port', config('mail.mailers.smtp.port')),
            'mail_username' => Setting::get('mail_username', config('mail.mailers.smtp.username')),
            'mail_encryption' => Setting::get('mail_encryption', config('mail.mailers.smtp.encryption')),
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name')),
            'mail_password_set' => (bool) Setting::get('mail_password'),
        ];

        return view('admin.system-settings.index', compact('settings'));
    }

    public function updateMaintenanceMode(Request $request)
    {
        $enabled = $request->boolean('maintenance_mode');

        Setting::set('maintenance_mode', $enabled ? '1' : '0');

        return back()->with(
            'success',
            $enabled
                ? 'Maintenance mode enabled — the site is now offline for regular users.'
                : 'Maintenance mode disabled — the site is live again.'
        );
    }

    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl,',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        foreach ([
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
        ] as $key) {
            Setting::set($key, $validated[$key] ?? '');
        }

        if (!empty($validated['mail_password'])) {
            Setting::set('mail_password', encrypt($validated['mail_password']));
        }

        return back()->with('success', 'Email settings updated successfully.');
    }

    public function sendTestEmail()
    {
        try {
            Mail::raw(
                'This is a test email from GenServis confirming your email settings are working.',
                function ($message) {
                    $message->to(auth()->user()->email)
                        ->subject('GenServis Test Email');
                }
            );

            return back()->with('success', 'Test email sent to ' . auth()->user()->email . '.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
