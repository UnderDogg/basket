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
 * Merchant Entity
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
 * @package PayBreak\Sdk\Entities
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
