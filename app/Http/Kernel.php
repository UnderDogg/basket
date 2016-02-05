<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class Kernel extends HttpKernel
{
    /**
     * Kernel constructor.
     *
     * @param Application $app
     * @param Router      $router
     */
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);

        array_walk($this->bootstrappers, function(&$bootstrapper)
        {
            if($bootstrapper === 'Illuminate\Foundation\Bootstrap\ConfigureLogging')
            {
                $bootstrapper = 'Bootstrap\ConfigureLogging';
            }
        });
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\AvailableInstallations::class,
        \App\Http\Middleware\Messages::class,
        \App\Http\Middleware\Download::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'userActionMerchant' => \App\Http\Middleware\UserActionMerchant::class,
        'permission' => \App\Http\Middleware\AuthorisePermission::class,
        'role' => \App\Http\Middleware\AuthoriseRole::class,
    ];
}
