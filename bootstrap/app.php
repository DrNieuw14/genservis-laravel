<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([

            'role' => \App\Http\Middleware\CheckRole::class,

            'permission' => \App\Http\Middleware\PermissionMiddleware::class,

        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\ForcePasswordChange::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // A stale page (session expired from inactivity, or a genuinely old
        // CSRF token) submitting any form — login, logout, whatever — used
        // to show Laravel's raw "419 Page Expired" error. Redirect to login
        // with a plain-language explanation instead, same as an ordinary
        // expired-session experience elsewhere in the app.
        //
        // Note: Handler::prepareException() converts TokenMismatchException
        // into a plain Symfony HttpException(419) BEFORE custom render()
        // callbacks are checked — a callback type-hinted for
        // TokenMismatchException itself never matches, silently falling
        // through to the default 419 view. Has to be caught as HttpException
        // + status code check instead.
        // Do NOT call session()->save() here — Illuminate\Routing\Pipeline
        // catches exceptions per middleware stage and feeds the rendered
        // response back in as if that stage returned normally, so
        // StartSession's own tail code (which calls save() once, after
        // $next() "returns") still runs after this closure. Calling save()
        // manually double-ages the flash data — StartSession's own second
        // ageFlashData() pass then immediately forgets whatever this closure
        // just flashed, wiping it before the browser ever sees it.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
            }
        });

    })->create();