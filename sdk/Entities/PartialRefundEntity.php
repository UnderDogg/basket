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
 * Partial Refund Entity
 *
 * @author LH
 * @package PayBreak\Sdk\Entities
 *
 * @method $this setId(int $id)
 * @method int|null getId()
 * @method $this setApplication(int $application)
 * @method int|null getApplication()
 * @method $this setStatus(string $status)
 * @method string|null getStatus()
 * @method $this setRefundAmount(int $refundAmount)
 * @method int|null getRefundAmount()
 * @method $this setEffectiveDate(string $effectiveDate)
 * @method string|null getEffectiveDate()
 * @method $this setRequestedDate(string $requestedDate)
 * @method string|null getRequestedDate()
 * @method $this setDescription(string $description)
 * @method string|null getDescription()
 */
class PartialRefundEntity extends AbstractEntity
{
    protected $properties = [
        'id' => self::TYPE_INT,
        'application' => self::TYPE_INT,
        'status' => self::TYPE_STRING,
        'refund_amount' => self::TYPE_INT,
        'effective_date' => self::TYPE_STRING,
        'requested_date' => self::TYPE_STRING,
        'description' => self::TYPE_STRING,
    ];
}
