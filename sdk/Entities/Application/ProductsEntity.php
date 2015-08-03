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
 * Products Entity
 *
 * @author WN
 * @method $this setGroup(string $group)
 * @method string|null getGroup()
 * @method $this setOptions(array $options)
 * @method array|null getOptions()
 * @method $this setDefault(string $default)
 * @method string|null getDefault()
 * @package PayBreak\Sdk\Entities
 */
class ProductsEntity extends AbstractEntity
{
    protected $properties = [
        'group'     => self::TYPE_STRING,
        'options'   => self::TYPE_ARRAY,
        'default'   => self::TYPE_STRING,
    ];
}
