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

use PayBreak\Sdk\Gateways\SettlementGateway;

/**
 * Settlement Gateway Test
 *
 * @author WN
 * @package Tests\Basket\Gateways
 */
class SettlementGatewayTest extends \TestCase
{
    public function testInstance()
    {
        /** @var \App\Gateways\ApiClientFactory $mock */
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $this->assertInstanceOf('PayBreak\Sdk\Gateways\SettlementGateway', new SettlementGateway($mock));
    }

    public function testGetSettlementReports()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willReturn([['id' => 1]]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $settlementGateway = new SettlementGateway($mock);

        $this->assertInternalType('array', $settlementGateway->getSettlementReports('xxxx'));
    }

    public function testGetSingleSettlementReport()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willReturn([['id' => 1]]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $settlementGateway = new SettlementGateway($mock);

        $this->assertInternalType('array', $settlementGateway->getSingleSettlementReport('xxxx', 12));
    }
}
