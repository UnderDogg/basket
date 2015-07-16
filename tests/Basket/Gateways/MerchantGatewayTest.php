<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Basket\Gateways;

use App\Basket\Gateways\MerchantGateway;

/**
 * Merchant Gateway Test
 *
 * @author WN
 * @package Tests\Basket\Gateways
 */
class MerchantGatewayTest extends \TestCase
{
    /**
     * @author WN
     */
    public function testInstance()
    {
        /** @var \App\Gateways\ApiClientFactory $mock */
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $this->assertInstanceOf('App\Basket\Gateways\MerchantGateway', new MerchantGateway($mock));
    }

    public function testGetMerchant()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $merchantGateway = new MerchantGateway($mock);

        $this->assertInstanceOf('App\Basket\Entities\MerchantEntity', $merchant = $merchantGateway->getMerchant(1, 'xxxx'));

        $this->assertSame(1, $merchant->getId());
    }

    public function testGetMerchantException()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willThrowException(new \Exception());

        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $merchantGateway = new MerchantGateway($mock);

        $this->setExpectedException('App\Exceptions\Exception', 'Problem fetching Merchant data form Provider API');

        $merchantGateway->getMerchant(1, 'xxx');
    }
}
