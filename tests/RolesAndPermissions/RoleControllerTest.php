<?php

use App\User;
use App\Http\Controllers;
use App\Role;

class RolesControllerTest extends TestCase
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
     * Test Add New Role Form
     *
     * @author MS
     */
    public function test_add_new_role_form()
    {
        // Test page gives 200 response
        $this->visit('/roles/create')
            ->seeStatusCode(200);

        // Test $parmissionsAvailable variable is available for use
        $this->call('GET', '/roles/create');
        $this->assertViewHas('permissionsAvailable');

        // Load view data and test variable data exists
        $response = $this->action('GET', 'RolesController@create');
        $view = $response->original;

        $permissionVar = $view['permissionsAvailable'][0];

        $this->assertNotEmpty( $permissionVar['id']             );
        $this->assertNotEmpty( $permissionVar['name']           );
        $this->assertNotEmpty( $permissionVar['display_name']   );
        $this->assertNotEmpty( $permissionVar['description']    );
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
        // Test page gives 200 response
        $this->visit('/roles')
            ->seeStatusCode(200)
            // Test clicking 'New Role' button links correctly
            ->click('addNewButton')
            ->seeStatusCode(200);

        // Test $role variable is available for use
        $this->call('GET', '/roles');
        $this->assertViewHas('role');
    }

    /**
     * Test Show Role And Permissions
     *
     * @author MS
     */
    public function test_show_role_and_permissions()
    {
        // Test page gives 200 response
        $this->visit('/roles/1')
            ->seeStatusCode(200);

        // Test $role variable is available for use
        $response = $this->call('GET', '/roles/1');
        $this->assertViewHas('role');

        // Load view data and test variable data exists
        $view = $response->original;
        $roleVar = $view['role'];


        $this->assertNotEmpty( $roleVar['id']                   );
        $this->assertNotEmpty( $roleVar['name']                 );
        $this->assertNotEmpty( $roleVar['display_name']         );
        $this->assertNotEmpty( $roleVar['description']          );
    }

    /**
     * Test Role Stored In Database
     *
     * @author MS
     */
    public function test_role_stored_in_database()
    {
        // Test 'New Role' page adds new role of form submission
        $this->visit('/roles/create')
            ->type('UnitTest','name')
            ->type('Unit Test', 'display_name')
            ->type('Unit Test Description', 'description')
            ->press('creatRoleButton');

        // Test new Role has been added to mock database
        $roleData = Role::all()->last();

        $this->assertEquals( 'UnitTest', $roleData->name                                    );
        $this->assertEquals( 'Unit Test', $roleData->display_name                           );
        $this->assertEquals( 'Unit Test Description', $roleData->description                );
        $this->assertEmpty($roleData->permissions);
    }

    /**
     * Test Edit Role And Permissions Form
     *
     * @author MS
     */
    public function test_edit_role_and_permissions_form()
    {
        // Test page gives 200 response
        $this->visit('/roles/1/edit')
            ->seeStatusCode(200)
            // Test clicking 'New Role' button links correctly
            ->press('saveChanges')
            ->seeStatusCode(200)
            // Test should receive success message on redirect
            ->withSession(['message']);

        // Test $role variable is available for use
        $response = $this->call('GET', '/roles/1/edit');
        $this->assertViewHas('role');

        // Load view data and test variable data exists
        $view = $response->original;
        $roleVar = $view['role'];

        $this->assertNotEmpty( $roleVar['id']                   );
        $this->assertNotEmpty( $roleVar['name']                 );
        $this->assertNotEmpty( $roleVar['display_name']         );
        $this->assertNotEmpty( $roleVar['description']          );

    }

    /**
     * Test Roles And Permissions Update
     *
     * @author MS
     */
    public function test_roles_and_permissions_update()
    {
        // Test 'Update Role' page updates a role from form submission
        $this->visit('/roles/1/edit')
            ->type('UnitTest','name')
            ->type('Unit Test', 'display_name')
            ->type('Unit Test Description', 'description')
            ->press('saveChanges')
            ->seePageIs('/roles/1/edit');

        // Test new Role has been added to mock database
        $roleData = Role::find(1);

        $this->assertEquals( 'UnitTest', $roleData->name                        );
        $this->assertEquals( 'Unit Test', $roleData->display_name               );
        $this->assertEquals( 'Unit Test Description', $roleData->description    );
    }
}
