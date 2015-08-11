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

use PayBreak\Sdk\Gateways\ApplicationGateway;

/**
 * Application Gateway Test
 *
 * @author WN
 * @package Tests\Basket\Gateways
 */
class ApplicationGatewayTest extends \TestCase
{
    public function testInstance()
    {
        /** @var \App\Gateways\ApiClientFactory $mock */
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $this->assertInstanceOf('PayBreak\Sdk\Gateways\ApplicationGateway', new ApplicationGateway($mock));
    }

    public function testGetApplication()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willReturn(['id' => 123]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new ApplicationGateway($mock);

        $this->assertInstanceOf(
            'PayBreak\Sdk\Entities\ApplicationEntity',
            $application = $applicationGateway->getApplication(1, 'xxxx')
        );

        $this->assertSame(123, $application->getId());
    }

    public function testFulfilApplication()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('post')->willReturn([]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new ApplicationGateway($mock);

        $this->assertTrue($applicationGateway->fulfilApplication(1, 'xxxx'));
    }

    public function testCancelApplication()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('post')->willReturn([]);
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new ApplicationGateway($mock);

        $this->assertTrue($applicationGateway->cancelApplication(1, 'Description', 'xxxx'));
    }
}
