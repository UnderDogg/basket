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
        Artisan::call('db:seed', ['--class' => 'DevSeeder']);

        $user = User::find(2);
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
     */
    public function test_create()
    {
        // Test page gives 200 response
        $this->visit('/locations/create')
            ->seeStatusCode(200);
    }

    /**
     * @author WN
     */
    public function testShow()
    {
        // Test page gives 200 response
        $this->visit('/locations/1')
            ->seeStatusCode(200);
    }

    /**
     * @author WN
     */
    public function testEdit()
    {
        // Test page gives 200 response
        $this->visit('/locations/1/edit')
            ->seeStatusCode(200);
    }

    /**
     * @author WN
     */
    public function testDelete()
    {
        // Test page gives 200 response
        $this->visit('/locations/1/delete')
            ->seeStatusCode(200);
    }

    /**
     * Test new locations button
     * @author EA
     */
    public function test_new_locations_button()
    {
        // Test page gives 200 response
        $this->visit('/locations')
            ->click('Add New Location')
            ->see('Create Location');
    }


    /**
     * Test new locations form
     * @author EA
     */
    public function test_new_locations_form()
    {
        $this->visit('/locations/create')
            ->type('TestLocation1Ref', 'reference')
            ->type('TestLocation1', 'name')
            ->type('TestLocation1@email.com', 'email')
            ->type('TestLocation1 Address', 'address')
            ->press('Create Location')
            ->seePageIs('/locations')
            ->see('TestLocation1Ref');
    }

    /**
     * Used for testing validation on edit location details page
     *
     * @author EB
     * @param string $name
     * @param string $email
     * @param string $address
     */
    public function typeEditDetails($name, $email, $address)
    {
        $this->visit('locations/1/edit')
            ->seeStatusCode(200)
            ->type($name, 'name')
            ->type($email, 'email')
            ->type($address, 'address')
            ->press('Save Changes')
            ->seePageIs('/locations/1/edit');
    }
}
