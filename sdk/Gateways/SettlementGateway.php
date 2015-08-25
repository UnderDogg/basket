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
     * @param string $token
     * @param string|null $since
     * @param string|null $until
     * @return array
     */
    public function getSettlementReports($token, $since = null, $until = null)
    {
        return $this->fetchDocument(
            '/v4/settlement-reports',
            $token,
            'Settlement',
            [
                'since' => $since,
                'until' => $until,
            ]
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
