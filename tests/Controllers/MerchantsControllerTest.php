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

class MerchantsControllerTest extends TestCase
{
    /**
     * @author WN, MS
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
    public function test_add_new_merchants_form()
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
    public function test_index_page()
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
            ->type('a702ae4ad59e47f5991cf4857bb75033', 'token')
            ->press('Save Changes')
            ->see('Merchant details were successfully updated');
    }

    /**
     * @author EA
     */
    public function testNewMerchant(){
        $this->createNewMerchant('Scan','a702ae4ad59e47f5991cf4857bb75033');
        $this->seePageIs('/merchants');
    }

    /**
     * @author EA
     */
    public function testEditMerchantValidation(){
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
    public function testNewMerchantDuplicationValidation(){
        $this->createNewMerchant('Scan','a702ae4ad59e47f5991cf4857bb75033');
        $this->createNewMerchant('Scan','a702ae4ad59e47f5991cf4857bb75033');
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
     * @author EA
     */
    private function createNewMerchant($merchantName,$token)
    {
        $this->visit('/merchants/create')
            ->type($merchantName, 'name')
            ->type($token, 'token')
            ->press('Create Merchant');
    }
}
