<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\User;
use Illuminate\Support\Facades\Artisan;

/**
 * Installation Synchronisation Service Test
 *
 * @author EB
 */
class InstallationSynchronisationServiceTest extends BrowserKitTestCase
{
    /**
     * @author EB
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate:refresh');
        Artisan::call('db:seed', ['--class' => 'DevSeeder']);

        $user = User::find(1);
        $this->be($user);
    }

    /**
     * Tear Down
     *
     * Required for using Mockery
     *
     * @author EB
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @author EB
     */
    public function testSynchroniseInstallation()
    {
        $response = [
            'return_url' => 'go.com',
        ];

        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn($response);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $installationGateway = new \PayBreak\Sdk\Gateways\InstallationGateway($mock);
        $service = new \App\Basket\Synchronisation\InstallationSynchronisationService($installationGateway);

        $rtn = $service->synchroniseInstallation(1);

        $this->assertInstanceOf(\App\Basket\Installation::class, $rtn);
        $this->assertSame(1, $rtn->id);
        $this->assertSame($rtn->ext_return_url, 'go.com');
    }

    /**
     * @author EB
     */
    public function testSynchroniseInstallationForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')
            ->willThrowException(new Exception('Synchronise Installation Failed'));

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $installationGateway = new \PayBreak\Sdk\Gateways\InstallationGateway($mock);
        $service = new \App\Basket\Synchronisation\InstallationSynchronisationService($installationGateway);

        $this->setExpectedException('Exception', 'Problem with get: Installation data form Provider API');
        $service->synchroniseInstallation(1);
    }

    /**
     * @author EB
     */
    public function testSynchroniseAllInstallationsForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')
            ->willThrowException(new Exception('Synchronise Installations Failed'));

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $installationGateway = new \PayBreak\Sdk\Gateways\InstallationGateway($mock);
        $service = new \App\Basket\Synchronisation\InstallationSynchronisationService($installationGateway);

        $this->setExpectedException(
            'WNowicki\Generic\Exception',
            'Problem fetching collection of [\PayBreak\Sdk\Entities\InstallationEntity] form Provider API'
        );
        $service->synchroniseAllInstallations(1);
    }
}
