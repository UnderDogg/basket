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

class ApplicationSynchronisationServiceTest extends TestCase
{
    /**
     * @author EB
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
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
    public function testSynchroniseApplication()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->createApplicationForTest();
        $this->assertInstanceOf(\App\Basket\Application::class, $service->synchroniseApplication(1));
    }

    /**
     * @author EB
     * @throws Exception
     */
    public function testSynchroniseApplicationForException()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willThrowException(new Exception());

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->setExpectedException(Exception::class, 'Problem with get: Application data form Provider API');
        $this->createApplicationForTest();
        $service->synchroniseApplication(1);
    }

    public function testLinkApplicationForInstallationException()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->setExpectedException(Exception::class, 'Installation not found');
        $service->linkApplication(1, 'NotAnInstallation');
    }

    /**
     * @author EB
     * @throws Exception
     */
    public function testLinkApplication()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([
            'application_address' => ['postcode' => 'test']
        ]);

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $return = $service->linkApplication(1, 'TestInstall');
        $this->assertInstanceOf(\App\Basket\Application::class, $return);
        $this->assertSame('test', $return->ext_application_address_postcode);
    }

    /**
     * @author EB
     * @throws Exception
     */
    public function testLinkApplicationForException()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willThrowException(new Exception());

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->setExpectedException(
            WNowicki\Generic\Exception::class,
            'Problem with get: Application data form Provider API'
        );
        $service->linkApplication(1, 'TestInstall');
    }

    /**
     * @author EB
     */
    public function testFulfil()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(true);

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->createApplicationForTest();
        $this->assertTrue($service->fulfil(1));
    }

    /**
     * @author EB
     * @throws Exception
     */
    public function testFulfilForException()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willThrowException(new Exception());

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->createApplicationForTest();
        $this->setExpectedException(
            'WNowicki\Generic\Exception',
            'Problem with post: Application data form Provider API'
        );
        $service->fulfil(1);
    }

    /**
     * @author EB
     * @throws Exception
     */
    public function testRequestCancellation()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(true);

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->createApplicationForTest();
        $this->assertTrue($service->requestCancellation(1, 'Description'));
    }

    /**
     * @author EB
     * @throws Exception
     */
    public function testRequestCancellationForException()
    {
        $mockApiClient = $this->getMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willThrowException(new Exception());

        $mock = $this->getMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->createApplicationForTest();
        $this->setExpectedException(
            'WNowicki\Generic\Exception',
            'Problem with post: Application data form Provider API'
        );
        $service->requestCancellation(1, 'Description');
    }
}
