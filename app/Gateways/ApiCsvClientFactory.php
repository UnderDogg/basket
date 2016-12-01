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

use PayBreak\Sdk\ApiClient\ApiClientFactoryInterface;
use PayBreak\Sdk\ApiClient\ProviderCsvApiClient;

/**
 * Api Csv Client Factory
 *
 * @author EA
 * @package App\Gateways
 */
class ApiCsvClientFactory implements ApiClientFactoryInterface
{
    /**
     * @author EA
     * @param string $token
     * @return ProviderApiClient
     */
    public function makeApiClient($token)
    {
        return ProviderCsvApiClient::make(config('basket.providerUrl'), $token, \Log::getMonolog());
    }
}
