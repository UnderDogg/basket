<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Psr\Log\LoggerInterface', function () { return \Log::getMonolog(); });

        $this->app->bind(
            'App\Basket\Notifications\LocationNotificationService',
            'App\Basket\Notifications\EmailLocationNotificationService'
        );
    }
}
