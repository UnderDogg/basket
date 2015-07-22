<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Entities;

use WNowicki\Generic\AbstractEntity;

/**
 * Application Entity
 *
 * @author WN
 * @package App\Basket\Entities
 */
class ApplicationEntity extends AbstractEntity
{
    protected $properties = [
        'id' => self::TYPE_INT,
        'posted_date' => self::TYPE_STRING,
        'current_status' => self::TYPE_STRING,
        'customer',
        'application_address',
        'installation' => self::TYPE_STRING,
        'order',
        'products',
        'fulfilment',
        'applicant',
        'metadata' => self::TYPE_ARRAY,
    ];
}
