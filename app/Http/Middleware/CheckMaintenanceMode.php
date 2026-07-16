<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class CheckMaintenanceMode
{
    /**
     * Blocks the site for everyone except:
     *  - the login/logout routes, so nobody can be locked out of authenticating
     *  - users who already hold `manage-system-settings`, so an admin can
     *    always reach the settings page to turn maintenance mode back off
     */
    public function handle(Request $request, Closure $next)
    {
        if (!(bool) Setting::get('maintenance_mode', false)) {
            return $next($request);
        }

        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->hasPermission('manage-system-settings')) {
            return $next($request);
        }

        return response()->view('maintenance', [], 503);
    }
}
