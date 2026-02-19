<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Trust ngrok proxy for HTTPS
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // 🔐 Custom middlewares
            'registry.head' => \App\Http\Middleware\CheckRegistryHead::class,
            'registry.staff' => \App\Http\Middleware\CheckRegistryStaff::class,
            'department.access' => \App\Http\Middleware\CheckDepartmentAccess::class,
            'admin' => \App\Http\Middleware\Admin::class,
            'non.admin' => \App\Http\Middleware\NonAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Render all non-HTTP exceptions as 500 error page in production
        $exceptions->render(function (Throwable $e, $request) {
            if (app()->environment('production') && !$e instanceof HttpExceptionInterface) {
                // Log the actual error for debugging
                report($e);

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Something went wrong. Please try again later.',
                    ], 500);
                }

                return response()->view('errors.500', [], 500);
            }
        });
    })
    ->create();
