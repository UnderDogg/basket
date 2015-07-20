<?php

use App\User;
use App\Http\Controllers;
use App\Basket\Location;

class LocationsControllerTest extends TestCase
{
    /**
     * Setup
     *
     * Runs before test to ensure we have a user logged in to gain access to the Locations CRUD
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
     * @todo Solution needed to move these asserts into their own test methods - either physical DB or trigger DevSeeder
     */
    public function test_create_and_edit()
    {
        // Test page gives 200 response
        $this->visit('/locations/create')
            ->seeStatusCode(200)

            // Test user can create new location
            ->type('unit','reference')
            ->type('1','installation_id')
            ->type('1','active')
            ->type('Unit Test','name')
            ->type('email@email.com','email')
            ->type('some address','address')
            ->press('createLocationButton')
            ->seeStatusCode(200)->withSession(['success'=>'New Location has been successfully created']);

        // Test location was created successfully
        $location = Location::findOrFail(1);
        $this->assertSame($location->reference, 'unit');
        $this->assertSame($location->installation_id, '1');
        $this->assertSame($location->active, '1');
        $this->assertSame($location->name, 'Unit Test');
        $this->assertSame($location->email, 'email@email.com');
        $this->assertSame($location->address, 'some address');

        // Test page gives 200 response
        $this->visit('/locations/1/edit')
            ->seeStatusCode(200)

            // Test user can edit a location
            ->type('99unit','reference')
            ->type('1','installation_id')
            ->type('0','active')
            ->type('99Unit Test','name')
            ->type('99email@email.com','email')
            ->type('99some address','address')
            ->press('saveChanges')
            ->seeStatusCode(200)->withSession(['success'=>'Location details were successfully updated']);

        // Test location was updated successfully
        $location = Location::findOrFail(1);
        $this->assertSame($location->reference, '99unit');
        $this->assertSame($location->installation_id, '1');
        $this->assertSame($location->active, '0');
        $this->assertSame($location->name, '99Unit Test');
        $this->assertSame($location->email, '99email@email.com');
        $this->assertSame($location->address, '99some address');

        // Test user can delete record
        $this->visit('/locations')
            ->seeStatusCode(200)
            ->press('delete1')
            ->seeStatusCode(200)->withSession(['success'=>'locations was successfully deleted']);
    }
}
