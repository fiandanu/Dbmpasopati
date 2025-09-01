<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $isNgrok = request()->getHost() && str_contains(request()->getHost(), 'ngrok');

        if (config('app.env') === 'production' && !$isNgrok) {
            URL::forceScheme('https');
        }
    }
}
