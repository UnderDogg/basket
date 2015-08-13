<?php

use App\User;
use App\Http\Controllers;
use App\Basket\Location;

class LocationsControllerTest extends TestCase
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
     * Basic Functionality Tests on Locations Page
     *
     * @author MS
     */
    public function test_index_page()
    {
        // Test page gives 200 response
        $this->visit('/locations')
            ->seeStatusCode(200);

        // Test $merchants variable is available for use
        $this->call('GET', '/locations');
        $this->assertViewHas('locations');
    }

    /**
     * Test Index Page
     *
     * Basic Functionality Tests on Locations Page
     *
     * @author MS
     * @todo Solution needed to persist min 1 record accross tests - either physical DB or trigger DevSeeder on testing
     */
    public function test_create_and_edit()
    {
        // Test page gives 200 response
        $this->visit('/locations/create')
            ->seeStatusCode(200);
    }
}
