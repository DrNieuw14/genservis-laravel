<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Accepts one or more permission slugs (e.g. `permission:view-ppmp` or
     * `permission:view-ppmp,manage-own-department-ppmp-items`) - the user
     * needs any one of them. Laravel splits the comma-separated middleware
     * parameter string itself and passes each piece as its own argument,
     * so this must be variadic rather than a single string parameter.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$permissions
    ): Response {

        $user = auth()->user();

        if (!$user) {

            abort(403);

        }

        $allowed = collect($permissions)
            ->contains(fn ($p) => $user->hasPermission(trim($p)));

        if (!$allowed) {

            abort(403, 'Unauthorized.');

        }

        return $next($request);
    }
}
