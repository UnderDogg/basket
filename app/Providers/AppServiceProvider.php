<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Can be removed when migrated to MySQL >= 5.7.7
        // See https://laravel-news.com/laravel-5-4-key-too-long-error
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Basket\Notifications\LocationNotificationService::class,
            \App\Basket\Notifications\EmailLocationNotificationService::class
        );

        $this->app->bind('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface', \App\Gateways\ApiClientFactory::class);

        $this->app->when('PayBreak\Sdk\Gateways\SettlementCsvGateway')
            ->needs('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface')
            ->give(\App\Gateways\ApiCsvClientFactory::class);
    }
}
