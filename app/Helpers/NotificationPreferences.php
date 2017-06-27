<?php

namespace App\Helpers;

use Flow\Bitwiser\AbstractBitwiser;

/**
 * Class Notifications
 *
 * @author GK
 * @package App\Helpers
 */
class NotificationPreferences extends AbstractBitwiser implements FlagFieldInterface
{
    const CONVERTED = 0;
    const DECLINED = 1;
    const REFERRED = 2;
}
