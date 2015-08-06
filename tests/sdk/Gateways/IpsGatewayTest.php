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
use PayBreak\Sdk\Gateways\IpsGateway;

/**
 * Ips Gateway Test
 *
 * @author WN
 * @package Tests\Basket\Gateways
 */
class IpsGatewayTest extends \TestCase
{
    public function testInstance()
    {
        /** @var \App\Gateways\ApiClientFactory $mock */
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $this->assertInstanceOf('PayBreak\Sdk\Gateways\IpsGateway', new IpsGateway($mock));
    }

    public function testListIpAddresses()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willReturn([['id' => 1]]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $ipsGateway = new IpsGateway($mock);

        $this->assertInternalType('array', $ips = $ipsGateway->listIpAddresses('xxxx'));

        $this->assertCount(1, $ips);

        $this->assertInstanceOf('PayBreak\Sdk\Entities\IpsEntity', $ips[0]);
    }

    public function testStoreIpAddresses()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('post')->willReturn([]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $ipsGateway = new IpsGateway($mock);

        $this->assertInternalType('array', $ips = $ipsGateway->storeIpAddress('xxxx', '123.123'));
    }

    public function testDeleteIpAddresses()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('delete')->willReturn([]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $ipsGateway = new IpsGateway($mock);

        $this->assertInternalType('array', $ips = $ipsGateway->deleteIpAddress('xxxx', 34));
    }
}
