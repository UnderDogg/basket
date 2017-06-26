<?php

namespace App\Helpers;

use Flow\Bitwiser\AbstractBitwiser;

/**
 * Class Notifications
 *
 * @author GK
 * @package App\Helpers
 */
class Notifications extends AbstractBitwiser
{
    const CONVERTED = 0;
    const DECLINED = 1;
    const REFERRED = 2;
}
