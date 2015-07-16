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
 * Class MerchantEntity
 *
 * @method $this setId(int $id)
 * @method int|null getId()
 * @method $this setCompanyName(string $companyName)
 * @method string|null getCompanyName()
 * @method $this setAddress(string $id)
 * @method string|null getAddress()
 * @method $this setProcessingDays(int $id)
 * @method int|null getProcessingDays()
 * @method $this setMinimumAmountSettled(int $id)
 * @method int|null getMinimumAmountSettled()
 * @method $this setAddressOnAgreements(string $id)
 * @method string|null getAddressOnAgreements()
 * @package App\Basket\Entities
 */
class MerchantEntity extends AbstractEntity
{
    protected $properties = [
        'id',
        'company_name',
        'address',
        'processing_days',
        'minimum_amount_settled',
        'address_on_agreements',
    ];
}
