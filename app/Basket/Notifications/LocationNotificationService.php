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

use App\Basket\Location;
use App\Basket\Application;

/**
 * Location Notification Service
 *
 * @author WN
 * @package App\Basket\Notifications
 */
interface LocationNotificationService
{
    /**
     * @param Application $application
     * @param Location $location
     * @return bool
     */
    public function convertedNotification(Application $application, Location $location);
}
