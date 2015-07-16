<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Gateways;

use App\Basket\Entities\MerchantEntity;
use App\Exceptions\Exception;
use WNowicki\Generic\ApiClient\ErrorResponseException;

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
        $api = $this->getApiFactory()->makeApiClient($token);

        try {

            $merchant = MerchantEntity::make($api->get('/v4/merchant'));

            $merchant->setAddress(json_encode($merchant->getAddress()));

            return $merchant->setId($id);

        } catch (ErrorResponseException $e) {

            throw new Exception($e->getMessage());

        } catch (\Exception $e) {

            $this->logError('MerchantGateway::getMerchant[' . $e->getCode() . ']: ' . $e->getMessage());

            throw new Exception('Problem fetching Merchant data form Provider API');
        }
    }
}
