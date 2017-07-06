<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Gateways;

use App\Gateways\ApiClientFactory;

class ApiClientFactoryTest extends \TestCase
{
    public function testMakeApiClient()
    {
        $factory = new ApiClientFactory();

        $this->assertInstanceOf('PayBreak\Sdk\ApiClient\ProviderApiClient', $factory->makeApiClient('testToken'));
    }
}
