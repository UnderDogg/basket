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

use PayBreak\Sdk\Entities\PartialRefundEntity;
use App\Exceptions\Exception;
use WNowicki\Generic\ApiClient\ErrorResponseException;

/**
 * Partial Refund Gateway
 *
 * @package PayBreak\Sdk\Gateways
 */
class PartialRefundGateway extends AbstractGateway
{
    /**
     * List Partial Refunds
     *
     * @author LH
     * @param $token
     * @return array
     * @throws Exception
     */
    public function listPartialRefunds($token)
    {
        $api = $this->getApiFactory()->makeApiClient($token);

        try {
            $installations = $api->get('/v4/partial-refunds');
            $rtn = [];

            foreach ($installations as $installation) {
                $rtn[] = PartialRefundEntity::make($installation);
            }

            return $rtn;

        } catch (ErrorResponseException $e) {

            throw new Exception($e->getMessage());

        } catch (\Exception $e) {

            $this->logError('PartialRefundGateway::getPartialRefunds[' . $e->getCode() . ']: ' . $e->getMessage());
            throw new Exception('Problem fetching partial refund data form Provider API');
        }
    }

    /**
     * Get Partial Refund
     *
     * @author LH
     * @param $token
     * @param $id
     * @return static
     * @throws Exception
     */
    public function getPartialRefund($token, $id)
    {
        return PartialRefundEntity::make($this->fetchDocument('/v4/partial-refunds/' . $id, $token, 'Partial Refund'));
    }
}
