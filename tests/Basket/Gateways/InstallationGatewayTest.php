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

use App\Basket\Gateways\InstallationGateway;
use WNowicki\Generic\ApiClient\ErrorResponseException;

class InstallationGatewayTest extends \TestCase
{
    public function testInstance()
    {
        /** @var \App\Gateways\ApiClientFactory $mock */
        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $this->assertInstanceOf('App\Basket\Gateways\InstallationGateway', new InstallationGateway($mock));
    }

    public function testGetMerchant()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $installationGateway = new InstallationGateway($mock);

        $this->assertInstanceOf(
            'App\Basket\Entities\InstallationEntity',
            $merchant = $installationGateway->getInstallation(1, 'xxxx')
        );
    }

    public function testGetMerchantException()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willThrowException(new \Exception());

        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $installationGateway = new InstallationGateway($mock);

        $this->setExpectedException('App\Exceptions\Exception', 'Problem fetching Installation data form Provider API');

        $installationGateway->getInstallation(1, 'xxx');
    }

    public function testGetMerchantErrorResponseException()
    {
        $mockApiClient = $this->getMock('App\Gateways\ProviderApiClient');

        $mockApiClient->expects($this->any())->method('get')->willThrowException(new ErrorResponseException('Test'));

        $mock = $this->getMock('App\Gateways\ApiClientFactory');

        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $installationGateway = new InstallationGateway($mock);

        $this->setExpectedException('App\Exceptions\Exception', 'Test');

        $installationGateway->getInstallation(1, 'xxx');
    }
}
