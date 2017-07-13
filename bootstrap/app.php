<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Monetary Locale
|--------------------------------------------------------------------------
|
| ...
|
*/

setlocale(LC_MONETARY, 'en_GB.UTF8');

/*
|--------------------------------------------------------------------------
| Configure Logging
|--------------------------------------------------------------------------
|
| This directive ensures logs are pushed to correct upstream handlers, such as
| Syslog to be handled by Loggly, AWS CloudWatch Logs or other handlers.
|
*/

$app->configureMonologUsing(function($monolog){

    if (env('LOG_SYSLOG', false)) {
        $syslogHandler = new SyslogHandler(env('LOG_PREFIX', 'BasketLog'), LOG_USER, LOG_NOTICE);
        $monolog->pushHandler($syslogHandler);
    }

    if (env('LOG_FILE', false)) {
        $streamHandler = new StreamHandler(storage_path('logs/laravel.log'), LOG_NOTICE);
        $monolog->pushHandler($streamHandler);
    }
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
