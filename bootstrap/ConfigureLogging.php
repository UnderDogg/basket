<?php

namespace Bootstrap;

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;

/**
 * Reconfigures Log Handlers to push out Log::{severity}() Calls to Syslog.
 * Optionally allow enable/disable of file based logging.
 *
 * Class ConfigureLogging
 *
 * @package Bootstrap
 * @author SL
 */
class ConfigureLogging extends BaseConfigureLogging
{
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSingleHandler(Application $app, Writer $log)
    {
        $syslogHandler = new SyslogHandler(env('LOG_PREFIX', 'BasketLog'), LOG_USER, LOG_NOTICE);

        $logger = $log->getMonolog();

        if (env('LOG_SYSLOG', false)) {
            $logger->pushHandler($syslogHandler);
        }

        if (env('LOG_FILE', false)) {
            $streamHandler = new StreamHandler(storage_path('logs/laravel.log'), LOG_NOTICE);
            $logger->pushHandler($streamHandler);
        }
    }
}
