<?php

use App\Http\Middleware\Activity;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Permissions;
use App\Http\Middleware\Roles;
use App\Http\Middleware\TwoFactor;
use Bepsvpt\SecureHeaders\SecureHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventStaleDatabaseReads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/public_facing.php',
            __DIR__.'/../routes/web.php',
        ],
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            // Served without the web group so the service worker never gets a session cookie.
            Route::group([], base_path('routes/scripts.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SecureHeadersMiddleware::class);

        $middleware->authenticateSessions();
        $middleware->appendToGroup('web', PreventStaleDatabaseReads::class);

        $middleware->statefulApi();
        $middleware->throttleApi('60,1');

        $middleware->trimStrings(except: [
            'password',
            'password_confirmation',
        ]);

        $middleware->preventRequestForgery(except: [
            'stripe/*',
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard'));

        // Behind a load balancer (e.g. AWS ELB), trust its forwarded headers:
        // $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR |
        //     Request::HEADER_X_FORWARDED_HOST |
        //     Request::HEADER_X_FORWARDED_PORT |
        //     Request::HEADER_X_FORWARDED_PROTO |
        //     Request::HEADER_X_FORWARDED_AWS_ELB);
        // $middleware->trustHosts();

        $middleware->alias([
            'two-factor' => TwoFactor::class,
            'admin' => Admin::class,
            'activity' => Activity::class,
            'permissions' => Permissions::class,
            'roles' => Roles::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*', 'ajax/*') || $request->expectsJson()
        );

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*', 'ajax/*')) {
                return response()->json(['message' => 'Page not found'], 404);
            }
        });
    })
    ->create();
