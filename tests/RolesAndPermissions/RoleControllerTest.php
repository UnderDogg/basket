<?php

use App\User;

class RoleControllerTest extends TestCase
{
    /**
     * Setup
     *
     * Runs before test to ensure we have a user logged in to gain access to the role CRUD
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
     * Test Index Page
     *
     * Basic Functionality Tests on Role Page
     *
     * @author MS
     */
    public function test_index_page()
    {
        $this->visit('/role')
            ->seeStatusCode(200)
            ->click('addNewButton')
            ->seeStatusCode(200);

        $this->call('GET', '/role');
        $this->assertViewHas('role');
    }

    /**
     *
     */
    public function test_add_new_role_form()
    {
        $this->visit('/role/create')
            ->seeStatusCode(200);

        $this->call('GET', '/role/create');
        $this->assertViewHas('permissionsAvailable');

        $response = $this->action('GET', 'RoleController@create');

        $view = $response->original;
        $this->assertNotEmpty($view['permissionsAvailable'][0]['id']);

    }
}