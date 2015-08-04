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

use App\Exceptions\Exception;

/**
 * Application Gateway
 *
 * @author MS
 * @package PayBreak\Sdk\Gateways
 */
class SettlementGateway extends AbstractGateway
{
    /**
     * @param $token
     * @param array $date_range
     * @return array
     * @throws Exception
     */
    public function getSettlementReports($token, array $date_range)
    {
        return $this->fetchDocument(
            '/v4/settlement-reports?since=' . $date_range['date_from'] . '&until=' . $date_range['date_to'],
            $token,
            'Settlement'
        );
    }

    /**
     * @param $token
     * @param $settlementId
     * @return array
     * @throws Exception
     */
    public function getSingleSettlementReport($token, $settlementId)
    {
        return $this->fetchDocument('/v4/settlement-reports/' . $settlementId, $token, 'Settlement Report');
    }
}
