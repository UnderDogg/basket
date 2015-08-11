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
 * Order Entity
 *
 * @author WN
 * @method $this setReference(string $reference)
 * @method string|null getReference()
 * @method $this setAmount(int $amount)
 * @method int|null getAmount()
 * @method $this setDescription(string $description)
 * @method string|null getDescription()
 * @method $this setValidity(string $validity)
 * @method string|null getValidity()
 * @package PayBreak\Sdk\Entities
 */
class OrderEntity extends AbstractEntity
{
    protected $properties = [
        'reference'     => self::TYPE_STRING,
        'amount'        => self::TYPE_INT,
        'description'   => self::TYPE_STRING,
        'validity'      => self::TYPE_STRING,
    ];
}
