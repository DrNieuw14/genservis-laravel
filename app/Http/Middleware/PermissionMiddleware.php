<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {

        $user = auth()->user();

        if (!$user) {

            abort(403);

        }

        if (!$user->hasPermission($permission)) {

            abort(403, 'Unauthorized.');

        }

        return $next($request);
    }
}