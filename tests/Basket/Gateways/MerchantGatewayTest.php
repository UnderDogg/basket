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

use PayBreak\Sdk\Gateways\MerchantGateway;
use WNowicki\Generic\ApiClient\ErrorResponseException;

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

        $this->assertInstanceOf('PayBreak\Sdk\Gateways\MerchantGateway', new MerchantGateway($mock));
    }

    public function testGetMerchant()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $merchantGateway = new MerchantGateway($mock);

        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $merchant = $merchantGateway->getMerchant(1, 'xxxx'));

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

    public function testGetMerchantErrorResponseException()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willThrowException(new ErrorResponseException('Test'));

        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $merchantGateway = new MerchantGateway($mock);

        $this->setExpectedException('App\Exceptions\Exception', 'Test');

        $merchantGateway->getMerchant(1, 'xxx');
    }
}
