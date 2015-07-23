<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Entities\Application;

use WNowicki\Generic\AbstractEntity;

/**
 * Fiance Entity
 *
 * @author WN
 * @method $this setLoanAmount(int $loanAmount)
 * @method int|null getLoanAmount()
 * @method $this setOrderAmount(int $orderAmount)
 * @method int|null getOrderAmount()
 * @method $this setDepositAmount(int $depositAmount)
 * @method int|null getDepositAmount()
 * @method $this setSubsidyAmount(int $subsidyAmount)
 * @method int|null getSubsidyAmount()
 * @method $this setSettlementNetAmount(int $settlementNetAmount)
 * @method int|null getSettlementNetAmount()
 * @package App\Basket\Entities
 */
class FinanceEntity extends AbstractEntity
{
    protected $properties = [
        'loan_amount'           => self::TYPE_INT,
        'order_amount'          => self::TYPE_INT,
        'deposit_amount'        => self::TYPE_INT,
        'subsidy_amount'        => self::TYPE_INT,
        'settlement_net_amount' => self::TYPE_INT,
    ];
}
