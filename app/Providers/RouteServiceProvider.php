<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')->group(function () {
                require base_path('routes/web.php');
                
                // KHUSUS PROVIDER
                require base_path('routes/provider/provider.php');
                require base_path('routes/provider/vpn.php');

                // KHUSUS UPT
                require base_path('routes/upt/pks.php');
                require base_path('routes/upt/reguller.php');
                require base_path('routes/upt/spp.php');
                require base_path('routes/upt/vpas.php');

                // KHUSUS PONPES
                require base_path('routes/ponpes/pks.php');
                require base_path('routes/ponpes/reguller.php');
                require base_path('routes/ponpes/spp.php');
                require base_path('routes/ponpes/vtren.php');
            });
        });
    }
}
