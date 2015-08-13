<?php

use App\User;
use App\Http\Controllers;
use App\Basket\Application;

class ApplicationsControllerTest extends TestCase
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
        $this->visit('/applications')
            ->seeStatusCode(200);

        // Test $merchants variable is available for use
        $this->call('GET', '/applications');
        $this->assertViewHas('applications');
    }
}
