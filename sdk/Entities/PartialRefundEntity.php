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
 */
class PartialRefundEntity extends AbstractEntity
{
    protected $properties = [
        'id',
        'application',
        'status',
        'refund_amount',
        'effective_date',
        'requested_date',
        'description',
    ];
}
