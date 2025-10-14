<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (config('app.env') === 'production' || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        Carbon::setLocale('en');


        $this->loadMigrationsFrom([

            // Db --------------------------------
            database_path('migrations'),
            database_path('migrations/db/upt'),
            database_path('migrations/db/ponpes'),


            // User --------------------------------
            database_path('migrations/user/upt'),
            database_path('migrations/user/ponpes'),
            database_path('migrations/user/provider_vpn'),
            database_path('migrations/user/kanwil_namaWilayah'),

        ]);
    }
}
