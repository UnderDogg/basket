<?php

use App\User;
use App\Http\Controllers;
use App\Role;
use App\RolePermissions;

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
     * Test Add New Role Form
     *
     * @author MS
     */
    public function test_add_new_role_form()
    {
        // Test page gives 200 response
        $this->visit('/role/create')
            ->seeStatusCode(200);

        // Test $parmissionsAvailable variable is available for use
        $this->call('GET', '/role/create');
        $this->assertViewHas('permissionsAvailable');

        // Load view data and test variable data exists
        $response = $this->action('GET', 'RoleController@create');
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
        $this->visit('/role')
            ->seeStatusCode(200)
            // Test clicking 'New Role' button links correctly
            ->click('addNewButton')
            ->seeStatusCode(200);

        // Test $role variable is available for use
        $this->call('GET', '/role');
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
        $this->visit('/role/1')
            ->seeStatusCode(200);

        // Test $role variable is available for use
        $response = $this->call('GET', '/role/1');
        $this->assertViewHas('role');

        // Load view data and test variable data exists
        $view = $response->original;
        $roleVar = $view['role'];
        $permissionVar = $view['role']['permissionsAvailable'][0];

        $this->assertNotEmpty( $roleVar['id']                   );
        $this->assertNotEmpty( $roleVar['name']                 );
        $this->assertNotEmpty( $roleVar['display_name']         );
        $this->assertNotEmpty( $roleVar['description']          );
        $this->assertNotEmpty( $permissionVar['id']             );
        $this->assertNotEmpty( $permissionVar['name']           );
        $this->assertNotEmpty( $permissionVar['display_name']   );
        $this->assertNotEmpty( $permissionVar['description']    );
    }

    /**
     * Test Role Stored In Database
     *
     * @author MS
     */
    public function test_role_stored_in_database()
    {
        // Test 'New Role' page adds new role of form submission
        $this->visit('/role/create')
            ->type('UnitTest','name')
            ->type('Unit Test', 'display_name')
            ->type('Unit Test Description', 'description')
            ->press('creatRoleButton');

        // Test new Role has been added to mock database
        $roleData = Role::all()->last();

        $this->assertEquals( 'UnitTest', $roleData->name                                    );
        $this->assertEquals( 'Unit Test', $roleData->display_name                           );
        $this->assertEquals( 'Unit Test Description', $roleData->description                );
        $this->assertEmpty(  RolePermissions::where('role_id', '=', $roleData->id)->get()   );
    }

    /**
     * Test Edit Role And Permissions Form
     *
     * @author MS
     */
    public function test_edit_role_and_permissions_form()
    {
        // Test page gives 200 response
        $this->visit('/role/1/edit')
            ->seeStatusCode(200)
            // Test clicking 'New Role' button links correctly
            ->press('saveChanges')
            ->seeStatusCode(200)
            // Test should receive success message on redirect
            ->withSession(['message']);

        // Test $role variable is available for use
        $response = $this->call('GET', '/role/1/edit');
        $this->assertViewHas('role');

        // Load view data and test variable data exists
        $view = $response->original;
        $roleVar = $view['role'];
        $permissionVar = $view['role']['permissionsAvailable'][0];

        $this->assertNotEmpty( $roleVar['id']                   );
        $this->assertNotEmpty( $roleVar['name']                 );
        $this->assertNotEmpty( $roleVar['display_name']         );
        $this->assertNotEmpty( $roleVar['description']          );
        $this->assertNotEmpty( $permissionVar['id']             );
        $this->assertNotEmpty( $permissionVar['name']           );
        $this->assertNotEmpty( $permissionVar['display_name']   );
        $this->assertNotEmpty( $permissionVar['description']    );

    }

    /**
     * Test Roles And Permissions Update
     *
     * @author MS
     */
    public function test_roles_and_permissions_update()
    {
        // Test 'Update Role' page updates a role from form submission
        $this->visit('/role/1/edit')
            ->type('UnitTest','name')
            ->type('Unit Test', 'display_name')
            ->type('Unit Test Description', 'description')
            ->type('', 'permissionsApplied')
            ->press('saveChanges')
            ->seePageIs('/role/1/edit');

        // Test new Role has been added to mock database
        $roleData = Role::find(1);

        $this->assertEquals( 'UnitTest', $roleData->name                        );
        $this->assertEquals( 'Unit Test', $roleData->display_name               );
        $this->assertEquals( 'Unit Test Description', $roleData->description    );
        $this->assertEmpty(  RolePermissions::where('role_id', '=', 1)->get()   );
    }

    /**
     * Test Roles and Associations Are Deleted
     *
     * @author MS
     */
    public function test_roles_and_associations_are_deleted()
    {
        // Test Delete Role from Role ID
        $roleController = new Controllers\RoleController();
        $roleController->destroy(1);

        // Test Role ID 1 and all related assoc permissions in association table are removed
        $this->assertEmpty( Role::find(1)                                       );
        $this->assertEmpty( RolePermissions::where('role_id', '=', 1)->get()    );

        // Press Delete button from role index page with name (ID 2)
        $this->visit('/role')
            ->press('delete2');

        // Test Role ID 2 and all related assoc permissions in association table are removed
        $this->assertEmpty( Role::find(2)                                       );
        $this->assertEmpty( RolePermissions::where('role_id', '=', 2)->get()    );

    }
}
