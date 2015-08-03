<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PayBreak\Sdk\Entities\Application;

use WNowicki\Generic\AbstractEntity;

/**
 * Fulfilment Entity
 *
 * @author WN
 * @method $this setMethod(string $method)
 * @method string|null getMethod()
 * @method $this setLocation(string $location)
 * @method string|null getLocation()
 * @package PayBreak\Sdk\Entities
 */
class FulfilmentEntity extends AbstractEntity
{
    protected $properties = [
        'method'    => self::TYPE_STRING,
        'location'  => self::TYPE_STRING,
    ];
}
