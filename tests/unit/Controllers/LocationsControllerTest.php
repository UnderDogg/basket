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

class LocationsControllerTest extends BrowserKitTestCase
{
    /**
     * @author WN, MS
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate:refresh');
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
    public function testIndexPage()
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
    public function testCreate()
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
    public function testNewLocationsButton()
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
    public function testNewLocationsForm()
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
     * @author EB
     */
    public function testUpdateSuccessful()
    {
        $this->typeEditDetails('Highest Location', 'High@Location.com', 'Location City');

        $this->see('Location details were successfully updated');
    }

    /**
     * @author EB
     */
    public function testUpdateRequiredFields()
    {
        $this->typeEditDetails('', '', '');

        $this->see('The name field is required.');
        $this->see('The email field is required.');
        $this->see('The address field is required');
    }

    /**
     * @author EB
     */
    public function testUpdateEmailField()
    {
        $this->typeEditDetails('Higher Location', 'NotAnEmailAddress', 'Location City');

        $this->see('Cannot update Location: Cannot validate NotAnEmailAddress as a valid email');
    }

    /**
     * @author EB
     */
    public function testUpdateReferenceField()
    {
        $this->withoutMiddleware();
        $this->patch('locations/1', [
                'reference' => '!@£$%^&*()_-+=',
                'name' => 'test',
                'email' => 'High@location.com',
                'address' => 'Location City',
            ]);

        $this->assertSessionHasErrors('reference', 'The reference format is invalid.');
    }

    /**
     * @author EB
     */
    public function testUpdateOnInvalidModel()
    {
        $this->withoutMiddleware();
        $this->patch('locations/0', [
            'reference' => 'Valid-Reference',
            'name' => 'test',
            'email' => 'High@location.com',
            'address' => 'Location City',
        ]);

        $messages = $this->app['session.store']->get('messages');
        $this->assertEquals($messages, [
            'error' => 'Could not found Location with ID:0',
        ]);
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
            ->seePageIs('/locations/1/edit')
            ->seeStatusCode(200);
    }

    /**
     * @author EB
     */
    public function testDestroy()
    {
        $this->withoutMiddleware();
        $response = $this->action('DELETE', 'LocationsController@destroy', ['id' => 1]);
        $this->assertEquals(302, $response->getStatusCode());

        $messages = $this->app['session.store']->get('messages');

        $this->assertEquals($messages, [
            'success' => 'Location was successfully deleted',
        ]);

        $this->assertRedirectedTo('locations');
    }

    public function testDestroyFromForm()
    {
        $this->visit('locations/1/delete')
            ->submitForm('Confirm')
            ->see('Location was successfully deleted')
            ->seePageIs('locations')
            ->seeStatusCode(200);
    }
}
