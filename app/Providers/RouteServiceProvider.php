<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api');
            // ->group(base_path('routes/api.php'));

            Route::middleware('web')->group(function () {
                require base_path('routes/web.php');

                // Route Database UPT
                require base_path('routes/db_upt/pks.php');
                require base_path('routes/db_upt/reguller.php');
                require base_path('routes/db_upt/spp.php');
                require base_path('routes/db_upt/vpas.php');
                require base_path('routes/db_upt/dashboard.php');

                // Route Database Ponpes
                require base_path('routes/db_ponpes/pks.php');
                require base_path('routes/db_ponpes/reguller.php');
                require base_path('routes/db_ponpes/spp.php');
                require base_path('routes/db_ponpes/vtren.php');
                require base_path('routes/db_ponpes/ponpesDashboard.php');

                // Route Tutorial
                require base_path('routes/tutorial/upt/reguller.php');
                require base_path('routes/tutorial/upt/vpas.php');
                require base_path('routes/tutorial/upt/mikrotik.php');
                require base_path('routes/tutorial/upt/server.php');
                require base_path('routes/tutorial/ponpes/reguller.php');
                require base_path('routes/tutorial/ponpes/vtren.php');

                // Route Monitoring Client
                require base_path('routes/mclient/upt/uptDashboard.php');
                require base_path('routes/mclient/upt/kunjungan.php');
                require base_path('routes/mclient/upt/pengiriman.php');
                require base_path('routes/mclient/upt/settingAlat.php');
                require base_path('routes/mclient/upt/vpas.php');
                require base_path('routes/mclient/upt/reguller.php');
                require base_path('routes/mclient/ponpes/ponpesDashboard.php');
                require base_path('routes/mclient/ponpes/settingponpes.php');
                require base_path('routes/mclient/ponpes/pengiriman.php');
                require base_path('routes/mclient/ponpes/vtren.php');
                require base_path('routes/mclient/ponpes/kunjungan.php');
                require base_path('routes/mclient/ponpes/reguller.php');
                require base_path('routes/mclient/catatankartu/catatan.php');
                require base_path('routes/mclient/catatankartu/vtren.php');
                require base_path('routes/mclient/grafik/grafikupt.php');
                require base_path('routes/mclient/grafik/grafikponpes.php');

                // Route untuk Provider dan Vpn
                require base_path('routes/provider/provider.php');
                require base_path('routes/provider/vpn.php');

                // route Kendala Dan PIC
                require base_path('routes/kendalapic/kendala.php');
                require base_path('routes/kendalapic/pic.php');

                // Route User
                require base_path('routes/user/upt.php');
                require base_path('routes/user/ponpes.php');
                require base_path('routes/user/kanwil.php');
                require base_path('routes/user/namawilayah.php');
                require base_path('routes/user/user_role.php');
            });
        });
    }
}
