<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Notifications;

use App\Basket\Application;
use App\Basket\Location;
use Psr\Log\LoggerInterface;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Email Location Notification Service
 *
 * @author WN
 * @package App\Basket\Notifications
 */
class EmailLocationNotificationService implements LocationNotificationService
{
    use PsrLoggerTrait;

    private $logger;

    public function __construct(LoggerInterface $loggerInterface = null)
    {
        $this->logger = $loggerInterface;
    }

    /**
     * @author WN
     * @param Application $application
     * @param Location $location
     * @return bool
     */
    public function convertedNotification(Application $application, Location $location)
    {
        \Mail::send(
            'emails.locations.converted',
            [
                'application' => $application,
                'location' => $location,
            ],
            function ($message) use ($location) {
                $message->to('dev@paybreak.com')->subject('Application Converted');

            }
        );

        $this->logInfo('LocationNotificationService: Converted Email sent for Application[' . $application->id . ']');

        return true;
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}

