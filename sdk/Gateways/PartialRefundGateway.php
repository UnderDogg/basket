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
        return $this->fetchCollection(
            $token,
            '/v4/partial-refunds',
            '\PayBreak\Sdk\Entities\PartialRefundEntity'
        );
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

    /**
     * @author LH
     * @param $token
     * @param $id
     * @param $refundAmount
     * @param $effectiveDate
     * @param $description
     * @throws Exception
     */
    public function requestPartialRefund($token, $id, $refundAmount, $effectiveDate, $description)
    {
        $api = $this->getApiFactory()->makeApiClient($token);

        try {
            $api->post(
                '/v4/applications/' . $id . '/request-partial-refund',
                [
                    'refund_amount' => $refundAmount,
                    'effective_date' => $effectiveDate,
                    'description' => $description,
                ]
            );

        } catch (ErrorResponseException $e) {

            $this->logWarning('PartialRefundGateway::requestPartialRefund[' . $e->getCode() . ']: ' . $e->getMessage());
            throw new Exception($e->getMessage());

        } catch (\Exception $e) {

            $this->logError('PartialRefundGateway::requestPartialRefund[' . $e->getCode() . ']: ' . $e->getMessage());
            throw new Exception('Problem requesting a partial refund on Provider API');
        }
    }
}
