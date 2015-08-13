<?php

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
        Artisan::call('db:seed', ['--class' => 'DBSeeder']);

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
}
