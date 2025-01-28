<?php

use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\AuthOrGuest;
use App\Http\Middleware\Localization;
use App\Http\Middleware\RedirectIfAdminAuthenticated;
use App\Http\Middleware\RedirectIfNotRole;
use App\Http\Middleware\RedirectIfStoreAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // admins routes
            Route::middleware('web')
                ->prefix('admins')
                ->name('admin.')
                ->group(base_path('routes/admins.php'));

            // suppliers routes
            Route::middleware('web')
                ->prefix('suppliers')
                ->name('supplier.')
                ->group(base_path('routes/suppliers.php'));

            // store routes
            Route::middleware('web')
                ->prefix('store')
                ->name('store.')
                ->group(base_path('routes/store.php'));

            // api routes
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/api_v1.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            Localization::class,
        ]);

        $middleware->alias([
            'auth.check:admin' => AuthenticateAdmin::class,
            'admin.auth' => RedirectIfAdminAuthenticated::class,
            'store.auth' => RedirectIfStoreAuthenticated::class,
            'auth_or_guest' => AuthOrGuest::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'role.check' => RedirectIfNotRole::class,
        ]);

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
