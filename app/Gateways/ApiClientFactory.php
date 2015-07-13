<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gateways;

/**
 * Api Client Factory
 *
 * @author WN
 * @package App\Gateways
 */
class ApiClientFactory
{
    /**
     * @author WN
     * @param $token
     * @return ProviderApiClient
     */
    public function makeApiClient($token)
    {
        return ProviderApiClient::make(config('basket.providerUrl'), $token);
    }
}
