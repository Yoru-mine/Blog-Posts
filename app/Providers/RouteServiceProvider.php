<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\\Http\\Controllers';

    public const HOME = '/home';

    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            $this->routes(function () {

                Route::middleware(['web', 'auth:admin'])
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));
            });

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }

    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
