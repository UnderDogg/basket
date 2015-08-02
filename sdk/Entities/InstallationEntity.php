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
 * Installation Entity
 *
 * @author WN
 * @method $this setId(string $id)
 * @method string|null getId()
 * @method $this setName(string $id)
 * @method string|null getName()
 * @method $this setReturnUrl(string $id)
 * @method string|null getReturnUrl()
 * @method $this setNotificationUrl(string $id)
 * @method string|null getNotificationUrl()
 * @method $this setDefaultProduct(string $id)
 * @method string|null getDefaultProduct()
 * @package PayBreak\Sdk\Entities
 */
class InstallationEntity extends AbstractEntity
{
    protected $properties = [
         'id',
         'name',
         'return_url',
         'notification_url',
         'default_product',
    ];
}
