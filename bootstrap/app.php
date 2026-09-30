<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\AuditRequest;
use App\Http\Middleware\RequirePermission;
use App\Http\Middleware\RequireRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->alias([
            'role' => RequireRole::class,
            'permission' => RequirePermission::class,
        ]);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            AuditRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Show the site's own error page instead of the framework default.
        // Server errors keep the debug screen while developing.
        $exceptions->respond(function (Response $response, Throwable $e, Request $request) {
            $status = $response->getStatusCode();
            $handled = app()->hasDebugModeEnabled() ? [403, 404] : [403, 404, 500, 503];

            if (! in_array($status, $handled, true) || $request->expectsJson() || $request->is('admin*', 'settings*')) {
                return $response;
            }

            try {
                return Inertia::render('Error', [
                    'status' => $status,
                    'site' => HandleInertiaRequests::siteProps(),
                    'auth' => ['user' => null],
                ])->toResponse($request)->setStatusCode($status);
            } catch (Throwable) {
                // If the database is unreachable the error page cannot be built either.
                return $response;
            }
        });
    })->create();
