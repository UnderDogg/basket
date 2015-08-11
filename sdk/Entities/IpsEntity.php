<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PayBreak\Sdk\Entities;

use WNowicki\Generic\AbstractEntity;

/**
 * Class IpsEntity
 *
 * @author EB
 * @method int|null getId()
 * @method $this setId(int $id)
 * @method string|null getIp()
 * @method $this setIp(string $ip)
 * @method bool|null getActive()
 * @method $this setActive(bool $active)
 *
 * @package PayBreak\Sdk\Entities
 */
class IpsEntity extends AbstractEntity
{
    protected $properties = [
        'id' => self::TYPE_INT,
        'ip' => self::TYPE_STRING,
        'active' => self::TYPE_BOOL,
    ];
}
