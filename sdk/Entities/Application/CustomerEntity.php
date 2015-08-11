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
 * Customer Entity
 *
 * @author WN
 * @method $this setTitle(string $title)
 * @method string|null getTitle()
 * @method $this setFirstName(string $firstName)
 * @method string|null getFirstName()
 * @method $this setLastName(string $lastName)
 * @method string|null getLastName()
 * @method $this setEmailAddress(string $emailAddress)
 * @method string|null getEmailAddress()
 * @method $this setPhoneHome(string $phoneHome)
 * @method string|null getPhoneHome()
 * @method $this setPhoneMobile(string $phoneMobile)
 * @method string|null getPhoneMobile()
 * @method $this setPostcode(string $postcode)
 * @method string|null getPostcode()
 * @package PayBreak\Sdk\Entities
 */
class CustomerEntity extends AbstractEntity
{
    protected $properties = [
        'title'         => self::TYPE_STRING,
        'first_name'    => self::TYPE_STRING,
        'last_name'     => self::TYPE_STRING,
        'email_address' => self::TYPE_STRING,
        'phone_home'    => self::TYPE_STRING,
        'phone_mobile'  => self::TYPE_STRING,
        'postcode'      => self::TYPE_STRING,
    ];
}
