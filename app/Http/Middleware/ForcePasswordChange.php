<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Blocks every page except logout until an account created with a
     * system-generated password (e.g. Quick Add Employee) sets its own.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->must_change_password) {
            return $next($request);
        }

        if ($request->routeIs('password.force.edit', 'password.force.update', 'logout')) {
            return $next($request);
        }

        return redirect()->route('password.force.edit');
    }
}
