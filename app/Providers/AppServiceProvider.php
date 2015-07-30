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

        $this->app->bind('App\Gateways\ApiClientFactory', 'App\Gateways\ApiClientFactory');
        $this->app->bind('App\Basket\Gateways\MerchantGateway', 'App\Basket\Gateways\MerchantGateway');
        $this->app->bind(
            'App\Basket\Synchronisation\MerchantSynchronisationService',
            'App\Basket\Synchronisation\MerchantSynchronisationService'
        );

        $this->app->bind('App\Basket\Gateways\InstallationGateway', 'App\Basket\Gateways\InstallationGateway');
        $this->app->bind(
            'App\Basket\Synchronisation\InstallationSynchronisationService',
            'App\Basket\Synchronisation\InstallationSynchronisationService'
        );

        $this->app->bind('App\Basket\Gateways\ApplicationGateway', 'App\Basket\Gateways\ApplicationGateway');
        $this->app->bind(
            'App\Basket\Synchronisation\ApplicationSynchronisationService',
            'App\Basket\Synchronisation\ApplicationSynchronisationService'
        );
        $this->app->bind(
            'App\Basket\Synchronisation\NotificationCatcherService',
            'App\Basket\Synchronisation\NotificationCatcherService'
        );
    }
}
