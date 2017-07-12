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

class ApplicationSynchronisationServiceTest extends BrowserKitTestCase
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willThrowException(new Exception());

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $this->setExpectedException(Exception::class, 'Problem with get: Application data form Provider API');
        $this->createApplicationForTest();
        $service->synchroniseApplication(1);
    }

    public function testLinkApplicationForInstallationException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([
            'application_address' => ['postcode' => 'test'],
            'is_regulated' => true
        ]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willThrowException(new Exception());

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(true);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willThrowException(new Exception());

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(true);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willThrowException(new Exception());

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
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

    /**
     * @author EB
     * @throws Exception
     */
    public function testRequestPartialRefund()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $partialRefundGateway = new \PayBreak\Sdk\Gateways\PartialRefundGateway($mock);
        $this->app->instance('\PayBreak\Sdk\Gateways\PartialRefundGateway', $partialRefundGateway);

        $this->createApplicationForTest();
        $this->assertNull($service->requestPartialRefund(1, 2000, '2016-01-01', 'Cancel'));
    }

    /**
     * @author EB
     * @throws Exception
     */
    public function testRequestPartialRefundForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $applicationGateway = new \PayBreak\Sdk\Gateways\ApplicationGateway($mock);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($applicationGateway);

        $mockApiClientPR = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClientPR->expects($this->any())->method('post')->willThrowException(new Exception(''));

        $mockPR = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mockPR->expects($this->any())->method('makeApiClient')->willReturn($mockApiClientPR);

        $partialRefundGateway = new \PayBreak\Sdk\Gateways\PartialRefundGateway($mockPR);
        $this->app->instance('\PayBreak\Sdk\Gateways\PartialRefundGateway', $partialRefundGateway);

        $this->createApplicationForTest();
        $this->setExpectedException(
            'PayBreak\Sdk\SdkException',
            'Problem requesting a partial refund on Provider API'
        );
        $this->assertNull($service->requestPartialRefund(1, 2000, '2016-01-01', 'Cancel'));
    }

    /**
     * @author EB
     * @throws \App\Exceptions\Exception
     */
    public function testInitialiseApplicationForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(['application' => 1234, 'url' => 'go.com']);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('initialiseApplication')->willThrowException(new Exception('Fail'));
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->setExpectedException('App\Exceptions\Exception', 'Fail');
        $service->initialiseApplication(
            \App\Basket\Location::first(),
            $this->getOrderEntity(),
            $this->getProductsEntity(),
            $this->getApplicantEntity(),
            User::find(1)
        );
    }

    /**
     * @author EB
     */
    public function testInitialiseApplication()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(['application' => 1234, 'url' => 'go.com']);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('initialiseApplication')->willReturn($this->getApplication());
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->assertInstanceOf(
            '\App\Basket\Application',
            $service->initialiseApplication(
                \App\Basket\Location::first(),
                $this->getOrderEntity(),
                $this->getProductsEntity(),
                $this->getApplicantEntity(),
                User::find(1)
            )
        );
    }

    /**
     * @author EB
     */
    public function testGetRemoteMerchantPayments()
    {
        $response = ['test' => 'response'];
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('getMerchantPayments')->willReturn($response);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $rtn = $service->getRemoteMerchantPayments($this->createApplicationForTest());

        $this->assertInternalType('array', $rtn);
        $this->assertEquals($response, $rtn);
    }

    /**
     * @author EB
     */
    public function testGetRemoteMerchantPaymentsForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('getMerchantPayments')
            ->willThrowException(new Exception('Get Merchant Payments Failed'));
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $rtn = $service->getRemoteMerchantPayments($this->createApplicationForTest());

        $this->assertInternalType('array', $rtn);
        $this->assertEquals([], $rtn);
    }

    /**
     * @author EB
     */
    public function testAddRemoteMerchantPayment()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('addMerchantPayment')->willReturn(null);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->assertTrue(
            $service->addRemoteMerchantPayment($this->createApplicationForTest(), \Carbon\Carbon::now(), 1)
        );
    }

    /**
     * @author EB
     */
    public function testAddRemoteMerchantPaymentForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('addMerchantPayment')
            ->willThrowException(new Exception('Add Merchant Payment Failed'));
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->setExpectedException('App\Exceptions\Exception', 'Add Merchant Payment Failed');
        $service->addRemoteMerchantPayment($this->createApplicationForTest(), \Carbon\Carbon::now(), 1);
    }

    /**
     * @author EB
     */
    public function testGetCreditInfoForApplication()
    {
        $response = ['test' => 'response'];
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('getApplicationCreditInfo')->willReturn($response);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->createApplicationForTest();
        $rtn = $service->getCreditInfoForApplication(1);

        $this->assertInternalType('array', $rtn);
        $this->assertSame($response, $rtn);
    }

    /**
     * @author EB
     */
    public function testGetCreditInfoForApplicationForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('getApplicationCreditInfo')
            ->willThrowException(new Exception('Get Credit Info For Application Failed'));
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->createApplicationForTest();

        $this->setExpectedException('Exception', 'Get Credit Info For Application Failed');
        $service->getCreditInfoForApplication(1);
    }

    /**
     * @author EB
     */
    public function testGetApplicationHistory()
    {
        $response = ['test' => 'response'];
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('getApplicationHistory')->willReturn($response);
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $rtn = $service->getApplicationHistory($this->createApplicationForTest());

        $this->assertInternalType('array', $rtn);
        $this->assertSame($response, $rtn);
    }

    /**
     * @author EB
     */
    public function testGetApplicationHistoryForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('getApplicationHistory')
            ->willThrowException(new Exception('Get Application History Failed'));
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->setExpectedException('Exception', 'Get Application History Failed');
        $service->getApplicationHistory($this->createApplicationForTest());
    }

    /**
     * @author EB
     */
    public function testInitialiseAssistedApplication()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(['application' => 1234, 'url' => 'go.com']);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('initialiseAssistedApplication')
            ->willReturn($this->getApplication());
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->assertInstanceOf(
            '\App\Basket\Application',
            $service->initialiseAssistedApplication(
                'test@email.com',
                \App\Basket\Location::first(),
                $this->getOrderEntity(),
                $this->getProductsEntity(),
                $this->getApplicantEntity(),
                User::find(1)
            )
        );
    }

    /**
     * @author EB
     */
    public function testInitialiseAssistedApplicationForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('post')->willReturn(['application' => 1234, 'url' => 'go.com']);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $appGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ApplicationGateway')->setConstructorArgs([$mock])
            ->getMock();
        $appGateway->expects($this->any())->method('initialiseAssistedApplication')
            ->willThrowException(new Exception('Fail'));
        $service = new \App\Basket\Synchronisation\ApplicationSynchronisationService($appGateway);

        $this->setExpectedException('App\Exceptions\Exception', 'Fail');
        $service->initialiseAssistedApplication(
            'test@email.com',
            \App\Basket\Location::first(),
            $this->getOrderEntity(),
            $this->getProductsEntity(),
            $this->getApplicantEntity(),
            User::find(1)
        );
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\OrderEntity
     */
    private function getOrderEntity()
    {
        return \PayBreak\Sdk\Entities\Application\OrderEntity::make(
            [
                'reference' => '123456789',
                'amount' => 50000,
                'description' => 'Test Order',
                'validity' => \Carbon\Carbon::now()->addDay()->toDateTimeString(),
                'deposit_amount' => 2000,
            ]
        );
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\ProductsEntity
     */
    private function getProductsEntity()
    {
        return \PayBreak\Sdk\Entities\Application\ProductsEntity::make(
            [
                'group' => 'xx',
                'options' => [],
                'default' => 'xx-xx',
            ]
        );
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\ApplicantEntity
     */
    private function getApplicantEntity()
    {
        return \PayBreak\Sdk\Entities\Application\ApplicantEntity::make([
            'title' => 'Mr',
            'first_name' => 'Test',
            'last_name' => 'Tester',
            'date_of_birth' => \Carbon\Carbon::now()->subYears(20)->toDateString(),
            'email_address' => 'test@test.com',
            'phone_home' => 03333333333,
            'phone_mobile' => 07777777777,
            'postcode' => 'TE55TP',
        ]);
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\FulfilmentEntity
     */
    private function getFulfilment()
    {
        return \PayBreak\Sdk\Entities\Application\FulfilmentEntity::make([
            'method' => 'xx',
            'location' => 'location',
        ]);
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\CustomerEntity
     */
    private function getCustomerEntity()
    {
        return \PayBreak\Sdk\Entities\Application\CustomerEntity::make([
            'title' => 'Mr',
            'first_name' => 'Test',
            'last_name' => 'Tester',
            'email_address' => 'test@test.com',
            'phone_home' => 03333333333,
            'phone_mobile' => 07777777777,
            'postcode' => 'TE55TP',
        ]);
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\AddressEntity
     */
    private function getAddressEntity()
    {
        return \PayBreak\Sdk\Entities\Application\AddressEntity::make([
            'postcode' => 'TE55TP',
        ]);
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\FinanceEntity
     */
    private function getFinanceEntity()
    {
        return \PayBreak\Sdk\Entities\Application\FinanceEntity::make([]);
    }

    /**
     * @author EB
     * @return \PayBreak\Sdk\Entities\Application\CancellationEntity
     */
    private function getCancellationEntity()
    {
        return \PayBreak\Sdk\Entities\Application\CancellationEntity::make([]);
    }

    /**
     * @return \PayBreak\Sdk\Entities\ApplicationEntity
     */
    private function getApplication()
    {
        $app = new \PayBreak\Sdk\Entities\ApplicationEntity();
        return $app->setId(1234)
            ->setPostedDate(\Carbon\Carbon::now()->toDateString())
            ->setCurrentStatus('1')
            ->setCustomer($this->getCustomerEntity())
            ->setApplicationAddress($this->getAddressEntity())
            ->setInstallation('Installation')
            ->setOrder($this->getOrderEntity())
            ->setProducts($this->getProductsEntity())
            ->setFulfilment($this->getFulfilment())
            ->setApplicant($this->getApplicantEntity())
            ->setFinance($this->getFinanceEntity())
            ->setCancellation($this->getCancellationEntity())
            ->setMetadata([])
            ->setResumeUrl('https://www.google.co.uk')
            ->setIsRegulated(true);
    }
}
