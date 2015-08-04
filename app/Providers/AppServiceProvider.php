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
        $this->app->bind('PayBreak\Sdk\Gateways\MerchantGateway', 'PayBreak\Sdk\Gateways\MerchantGateway');
        $this->app->bind('PayBreak\Sdk\Gateways\IpsGateway', 'PayBreak\Sdk\Gateways\IpsGateway');
        $this->app->bind(
            'App\Basket\Synchronisation\MerchantSynchronisationService',
            'App\Basket\Synchronisation\MerchantSynchronisationService'
        );

        $this->app->bind('PayBreak\Sdk\Gateways\InstallationGateway', 'PayBreak\Sdk\Gateways\InstallationGateway');
        $this->app->bind(
            'App\Basket\Synchronisation\InstallationSynchronisationService',
            'App\Basket\Synchronisation\InstallationSynchronisationService'
        );

        $this->app->bind('PayBreak\Sdk\Gateways\ApplicationGateway', 'PayBreak\Sdk\Gateways\ApplicationGateway');
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
