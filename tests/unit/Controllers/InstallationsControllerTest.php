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
use App\Http\Controllers;
use App\Basket\Installation;

class InstallationsControllerTest extends BrowserKitTestCase
{
    /**
     * @author WN, MS
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
     * Test Index Page
     *
     * Basic Functionality Tests on Merchant Page
     *
     * @author MS
     */
    public function testIndexPage()
    {
        // Test page gives 200 response
        $this->visit('/installations')
            ->seeStatusCode(200);

        // Test $merchants variable is available for use
        $this->call('GET', '/installations');
        $this->assertViewHas('installations');
    }

    /**
     * @author WN, EB
     */
    public function testShow()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $productGateway = new \PayBreak\Sdk\Gateways\ProductGateway($mock);
        $this->app->instance('PayBreak\Sdk\Gateways\ProductGateway', $productGateway);

        // Test page gives 200 response
        $this->visit('/installations/1')
            ->seeStatusCode(200);
    }

    /**
     * @author WN
     */
    public function testEdit()
    {
        // Test page gives 200 response
        $this->visit('/installations/1/edit')
            ->seeStatusCode(200);
    }

    /**
     * @author EB
     */
    public function testActivateNoneExistingInstallation()
    {
        $installation = new Installation();
        $installation->id = 20;
        try {
            $installation->activate();
        } catch (\Exception $e) {
            $this->assertEquals(
                'Trying to activate none existing Installation',
                $e->getMessage()
            );
        }
    }

    /**
     * @author EB
     */
    public function testDeactivateNoneExistingInstallation()
    {
        $installation = new Installation();
        $installation->id = 20;
        try {
            $installation->deactivate();
        } catch (\Exception $e) {
            $this->assertEquals(
                'Trying to deactivate none existing Installation',
                $e->getMessage()
            );
        }
    }

    /**
     * @author EB
     */
    public function testDeactivateChainsLocation()
    {
        $installation = Installation::query()->find(1);

        foreach ($installation->locations() as $l1) {
            $l1->active = 1;
        }

        $installation->deactivate();

        foreach ($installation->locations() as $loc) {
            $this->assertEquals(0, $loc->active);
            $this->assertNotEquals(1, $loc->active);
        }
    }

    /**
     * @author EB
     */
    public function testActivateMerchantException()
    {
        $installation = Installation::query()->find(1);

        try {
            $installation->activate();
        } catch (\Exception $e) {
            $this->assertEquals(
                'Can\'t activate Installation because Merchant is not active.',
                $e->getMessage()
            );
            $this->assertEquals(\App\Exceptions\Exception::class, get_class($e));
        }
    }

    /**
     * @author EB
     */
    public function testActivateChainsLocation()
    {
        $installation = Installation::query()->find(1);
        $merchant = \App\Basket\Merchant::findOrFail(1)->activate();

        foreach ($installation->locations() as $l1) {
            $l1->active = 0;
        }

        $installation->activate();

        foreach ($installation->locations() as $l2) {
            $this->assertEquals(1, $l2->active);
            $this->assertNotEquals(0, $l2->active);
        }
    }

    /**
     * @author EB
     */
    public function testGetLocationInstructionAsHtml()
    {
        $installation = Installation::query()->find(1);
        $installation->update(['location_instruction' => '## Test']);
        $instruction = Installation::findOrFail(1)->getLocationInstructionAsHtml();
        $this->assertEquals('<h2>Test</h2>', $instruction);
    }

    /**
     * @author EB
     */
    public function testGetDisclosureAsHtml()
    {
        $installation = Installation::query()->find(1);
        $installation->update(['disclosure' => '## Test Two']);
        $disclosure = Installation::findOrFail(1)->getDisclosureAsHtml();
        $this->assertEquals('<h2>Test Two</h2>', $disclosure);
    }

    /**
     * @author EB
     */
    public function testShowForException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $productGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ProductGateway')->setConstructorArgs([$mock])
            ->getMock();
        $productGateway->expects($this->any())->method('getProductGroupsWithProducts')
            ->willThrowException(new \Exception('Failed'));
        $this->app->instance('PayBreak\Sdk\Gateways\ProductGateway', $productGateway);

        $this->visit('/installations/1')
                ->seeStatusCode(200)
            ->see('Failed');
    }

    /**
     * @author EB
     */
    public function testShowForProductsEmptyException()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $productGateway = $this->getMockBuilder('\PayBreak\Sdk\Gateways\ProductGateway')->setConstructorArgs([$mock])
            ->getMock();
        $productGateway->expects($this->any())->method('getProductGroupsWithProducts')
            ->willThrowException(new \Exception('Products are empty'));
        $this->app->instance('PayBreak\Sdk\Gateways\ProductGateway', $productGateway);

        $this->visit('/installations/1')
            ->seeStatusCode(200)
            ->dontSee('Failed')
            ->dontSee('Products are empty')
            ->assertViewHas('products');
    }
}
