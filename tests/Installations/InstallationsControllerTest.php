<?php

use App\User;
use App\Http\Controllers;
use App\Basket\Installation;

class InstallationsControllerTest extends TestCase
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

    /**
     * @author WN
     */
    public function testShow()
    {
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
}
