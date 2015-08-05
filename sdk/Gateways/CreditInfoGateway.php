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

/**
 * Credit Info Gateway
 *
 * @author WN
 * @package App\Basket\Gateways
 */
class CreditInfoGateway extends AbstractGateway
{
    /**
     * @author WN
     * @param string $installation
     * @param int $amount
     * @param string $token
     * @return array
     * @throws \App\Exceptions\Exception
     */
    public function getCreditInfo($installation, $amount, $token)
    {
        return $this->fetchDocument(
            '/v4/installations/' . $installation . '/credit-information',
            $token,
            'Application',
            ['order_amount' => $amount]
        );
    }
}
