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
use App\Merchants;

class MerchantsControllerTest extends BrowserKitTestCase
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
     * Tear Down
     *
     * Required for using Mockery
     *
     * @author MS
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Test Add New Merchants Form
     *
     * @author MS
     */
    public function testAddNewMerchantsForm()
    {
        // Test page gives 200 response
        $this->visit('/merchants/create')
            ->seeStatusCode(200);
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
        $this->visit('/merchants')
            ->seeStatusCode(200)
            // Test clicking 'New Merchant' button links correctly
            ->click('addNewButton')
            ->seeStatusCode(200);

        // Test $merchants variable is available for use
        $this->call('GET', '/merchants');
        $this->assertViewHas('merchants');
    }

    /**
     * @author WN
     */
    public function testShow()
    {
        // Test page gives 200 response
        $this->visit('/merchants/1/edit')
            ->seeStatusCode(200);
    }

    /**
     * @author EA
     */
    public function testEdit()
    {
        // Test page gives 200 response
        $this->visit('/merchants/1/edit')
            ->type('Test Merchant2', 'name')
            ->type('1234567890qwertyuiopasdfghjklzxc', 'token')
            ->press('Save Changes')
            ->see('Merchant details were successfully updated');
    }

    /**
     * @author EA
     */
    public function testNewMerchant()
    {
        $this->createNewMerchant('TestMerchant', '1234567890qwertyuiopasdfghjklzxc');
        $this->seePageIs('/merchants/2');
    }

    /**
     * @author EA
     */
    public function testEditMerchantValidation()
    {
        $this->visit('/merchants/1/edit')
            ->type('  ', 'name')
            ->type('12', 'token')
            ->press('Save Changes')
            ->see('The name cannot be empty')
            ->see('The token must be 32 characters');
    }

    /**
     * @author EA
     */
    public function testNewMerchantDuplicationValidation()
    {
        $this->createNewMerchant('TestMerchant', '1234567890qwertyuiopasdfghjklzxc');
        $this->createNewMerchant('TestMerchant', '1234567890qwertyuiopasdfghjklzxc');
        $this->seePageIs('/merchants')
            ->see('Invalid merchant token');
    }

    /**
     * Test new merchants button
     * @author EA
     */
    public function testNewMerchantsButton()
    {
        // Test page gives 200 response
        $this->visit('/merchants')
            ->click('Add New Merchant')
            ->see('Create Merchant');
    }

    /**
     * Test new merchant form
     * @param $merchantName
     * @param $token
     * @author EA, EB
     */
    private function createNewMerchant($merchantName, $token)
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $merchantGateway = new \PayBreak\Sdk\Gateways\MerchantGateway($mock);
        $this->app->instance('PayBreak\Sdk\Gateways\MerchantGateway', $merchantGateway);

        $installationGateway = new \PayBreak\Sdk\Gateways\InstallationGateway($mock);
        $this->app->instance('PayBreak\Sdk\Gateways\InstallationGateway', $installationGateway);

        $this->visit('/merchants/create')
            ->type($merchantName, 'name')
            ->type($token, 'token')
            ->press('Create Merchant');
    }

    /**
     * @author EB
     */
    public function testActivateMerchant()
    {
        $merchant = \App\Basket\Merchant::query()->find(1);
        $merchant->activate();

        $this->assertEquals(true, $merchant->active);
    }

    /**
     * @author EB
     */
    public function testDeactivateNoneExistingMerchant()
    {
        $installation = new \App\Basket\Merchant();
        $installation->id = 20;
        try {
            $installation->deactivate();
        } catch (\Exception $e) {
            $this->assertEquals(
                'Trying to deactivate none existing Merchant',
                $e->getMessage()
            );
        }
    }

    /**
     * @author EB
     */
    public function testActivateNoneExistingMerchant()
    {
        $installation = new \App\Basket\Merchant();
        $installation->id = 20;
        try {
            $installation->activate();
        } catch (\Exception $e) {
            $this->assertEquals(
                'Trying to activate none existing Merchant',
                $e->getMessage()
            );
        }
    }

    /**
     * @author EB
     */
    public function testDeactivateChainsInstallation()
    {
        $merchant = \App\Basket\Merchant::query()->find(1);
        foreach ($merchant->installations() as $i1) {
            $i1->active = 1;
        }

        $merchant->deactivate();

        foreach ($merchant->installations() as $i2) {
            $this->assertEquals(1, $i2->active);
            $this->assertNotEquals(0, $i2->active);
        }
    }
}
