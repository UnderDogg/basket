<?php

use App\User;
use App\Http\Controllers;
use App\Basket\Installation;

class InstallationsControllerTest extends TestCase
{
    /**
     * Setup
     *
     * Runs before test to ensure we have a user logged in to gain access to the Merchants CRUD
     *
     * @author MS
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed');

        $user = new User(['name' => 'dev']);
        $this->be($user);
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
        $this->visit('/installations')
            ->seeStatusCode(200);

        // Test $merchants variable is available for use
        $this->call('GET', '/installations');
        $this->assertViewHas('installations');
    }
}
