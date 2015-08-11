<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PayBreak\Sdk\Gateways;

use PayBreak\Sdk\Entities\MerchantEntity;
use App\Exceptions\Exception;

/**
 * Class MerchantGateway
 *
 * @author WN
 * @package App\Gateways
 */
class MerchantGateway extends AbstractGateway
{
    /**
     * @param $id
     * @param $token
     * @return MerchantEntity
     * @throws Exception
     */
    public function getMerchant($id, $token)
    {
        $merchant = MerchantEntity::make($this->fetchDocument('/v4/merchant', $token, 'Merchant'));

        $merchant->setAddress(json_encode($merchant->getAddress()));

        return $merchant->setId($id);
    }
}
