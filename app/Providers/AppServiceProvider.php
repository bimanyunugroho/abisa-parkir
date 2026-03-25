<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        /*
         * If you want use HTTPS/SSL mode, please uncomment for this code.
         * But if you in HTTP/DEVELOPMENT mode, please comment for this code.
         *
         * In by default, is comment
         * */

         if (config('app.env') === 'production') {
             URL::forceScheme('https');
         }
    }
}
