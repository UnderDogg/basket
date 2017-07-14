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

class UsersControllerTest extends BrowserKitTestCase
{
    /**
     * @author EA
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate:refresh');
        Artisan::call('db:seed', ['--class' => 'DevSeeder']);

        $user = User::find(1);
        $this->be($user);
    }

    /**
     * Tear Down
     *
     * Required for using Mockery
     *
     * @author EA
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Test Index Page
     *
     * Initial test added
     *
     * @author EA
     */
    public function testIndex()
    {
        $this->visit('users')
            ->seeStatusCode(200);

        $this->call('GET', 'users');
        $this->assertViewHas('users');
    }

    /**
     * @author EB
     */
    public function testCreate()
    {
        $this->visit('users/create')
            ->assertViewHasAll(['user','merchants','rolesAvailable','rolesApplied']);
        $this->type('Test', 'name')
            ->type('test@dev.com', 'email')
            ->type('password', 'password')
            ->select(1, 'merchant_id')
            ->check('administrator')
            ->press('createUserButton')
            ->see('Users')
            ->see('New user has been successfully created')
            ->seeInDatabase('users', ['email' => 'test@dev.com']);
    }

    /**
     * @author EB
     */
    public function testCreateErrors()
    {
        $this->visit('users/create')
            ->assertViewHasAll(['user','merchants','rolesAvailable','rolesApplied']);
        $this->see('Create a new User')
            ->submitForm('createUserButton')
            ->see('The name cannot be empty')
            ->see('The name field is required')
            ->see('The email cannot be empty')
            ->see('The email field is required')
            ->see('The password cannot be empty')
            ->see('The password field is required');
    }

    /**
     * @author EB
     */
    public function testCreateHasRequired()
    {
        $this->call('GET', 'users/create');
        $this->assertResponseOk();
        $this->assertViewHas('user', null);

        $merchants = \App\Basket\Merchant::query()->get()->pluck('name', 'id')->toArray();
        $this->assertViewHas('merchants', $merchants);
    }

    /**
     * @author EB
     */
    public function testCreateHasRoles()
    {
        $this->call('GET', 'users/create');
        $this->assertResponseOk();
        $this->assertViewHas('user', null);

        $roles = $this->callPrivateMethodOnClass(
            'fetchRoles',
            [
                null,
            ],
            Controllers\UsersController::class
        );
        $this->assertViewHas('rolesAvailable', $roles['rolesAvailable']);
        $this->assertViewHas('rolesApplied', $roles['rolesApplied']);
    }

    /**
     * @author EB
     */
    public function testStoreThroughPost()
    {
        $user = $this->getPostUserData();
        $views = $this->call(
            'POST',
            'users',
            $user
        );
        $this->assertRedirectedTo('/users');
        $this->assertEquals(302, $views->getStatusCode());
        $this->assertSessionHas('messages', ['success' => 'New user has been successfully created']);
    }

    /**
     * @author EB
     */
    public function testFailStoreThroughPost()
    {
        $user = $this->getPostUserData('dev@paybreak.com');
        $view = $this->call(
            'POST',
            'users',
            $user
        );
        $this->assertRedirectedTo('/users/create');
        $this->assertEquals(302, $view->getStatusCode());
        $this->assertSessionHas('messages', ['error' => 'Cannot create User']);
        $this->notSeeInDatabase('users', $user);
    }

    /**
     * @author EB
     * @throws \App\Exceptions\RedirectException
     */
    public function testFailStore()
    {
        $request = $this->createRequestForTest($this->getPostUserData('dev@paybreak.com'), 'UserStoreRequest');
        $controller = new Controllers\UsersController();
        $this->setExpectedException(\App\Exceptions\RedirectException::class);
        $controller->store($request);
    }

    /**
     * @author EB
     */
    public function testStoreMerchantNotAllowed()
    {
        $user = User::find(2);
        $this->be($user);
        $user = $this->getPostUserData('test@dev.com', 2);
        $view = $this->call(
            'POST',
            'users',
            $user
        );
        $this->assertRedirectedTo('/users');
        $this->assertEquals(302, $view->getStatusCode());
        $this->assertSessionHas('messages', ['error' => 'You are not allowed to create User for this Merchant']);
    }

    /**
     * @author EB
     */
    public function testShow()
    {
        $this->visit('users/1')
            ->assertViewHas('user');

        $this->see('View User')
            ->see('Administrator')
            ->see('dev@paybreak.com')
            ->see('System Administrator')
            ->assertResponseStatus(200);
    }
    /**
     * @author EB
     */
    public function testSuEditUserLocations()
    {
        $this->visit('users/1/locations');
        $this->see('Super Users do not belong to a Merchant, cannot fetch Locations');

        $this->call('GET', 'users/1/locations');
        $this->assertRedirectedTo('users');
        $this->see('Users');
        $this->dontSee('Update User Locations');
    }

    /**
     * @author EB
     */
    public function testEditUserLocations()
    {
        $this->visit('users/2/locations');
        // $this->call('GET', 'users/2/locations');
        $this->see('Update User Locations');
        $this->check('Higher Location');
        $this->submitForm('Save Changes');
        $this->see('User details were successfully updated');
    }

    /**
     * @author EB
     */
    public function testEditPage()
    {
        $this->visit('users/1/edit');
        $this->see('Edit User');
    }

    /**
     * @author EB
     */
    public function testEditErrors()
    {
        $this->visit('users/1/edit');
        $this->see('Edit User');
        $this->type('', 'name')
            ->type('', 'email')
            ->type('', 'password')
            ->press('Save Changes')
            ->see('The name field is required.')
            ->see('The name cannot be empty')
            ->see('The email field is required.')
            ->see('The email cannot be empty');
    }

    /**
     * @author EB
     */
    public function testEditForm()
    {
        $this->visit('users/2/edit');
        $this->see('Edit User');
        $this->type('TestName', 'name');
        $this->press('Save Changes');
        $this->see('User details were successfully updated');
    }

    /**
     * @author EB
     */
    public function testDefaultEditThroughPost()
    {
        $user = $this->getPostUserData();
        $view = $this->call(
            'PATCH',
            'users/1',
            $user
        );
        $this->assertRedirectedTo('users/');
        $this->assertEquals(302, $view->getStatusCode());
        $this->assertSessionHas('messages', ['success' => 'User details were successfully updated']);
    }

    /**
     * @author EB
     */
    public function testActualEditThroughPost()
    {
        $user = $this->addMethodOntoRequest($this->getPostUserData('dev@pb.com'));
        $view = $this->call(
            'POST',
            'users/1',
            $user
        );
        $this->assertRedirectedTo('users/');
        $this->assertEquals(302, $view->getStatusCode());
        $this->assertSessionHas('messages', ['success' => 'User details were successfully updated']);
        $this->seeInDatabase('users', ['id' => '1', 'email' => 'dev@pb.com']);
        $this->notSeeInDatabase('users', ['id' => '1', 'email' => 'dev@paybreak.com']);
        $this->notSeeInDatabase('users', ['id' => '1', 'email' => 'test@dev.com']);
    }

    /**
     * Fails because the Email Address already exists (unique fails)
     *
     * @author EB
     */
    public function testEditFailThroughPost()
    {
        $user = $this->addMethodOntoRequest($this->getPostUserData('it@paybreak.com'));
        $view = $this->call(
            'POST',
            'users/1',
            $user
        );
        $this->assertRedirectedTo('/users/1/edit');
        $this->assertSessionHas(
            'messages',
            ['error' => 'Cannot update user [1]']
        );
    }

    /**
     * @author EB
     * @throws \App\Exceptions\RedirectException
     */
    public function testUpdateForException()
    {
        $request = $this->createRequestForTest($this->getPostUserData('it@paybreak.com'), 'UserUpdateRequest');
        $controller = new Controllers\UsersController();
        $this->setExpectedException(\App\Exceptions\RedirectException::class);
        $controller->update(1, $request);
    }

    public function testUpdateLocationsForm()
    {
        $this->visit('users/2/locations');
        $this->see('Update User Locations');
        $this->check('Higher Location');
        $this->press('Save Changes');
        $this->see('User details were successfully updated');
    }

    public function testFailUpdateLocationsUndefinedUser()
    {
        $request = $this->createRequestForTest($this->addMethodOntoRequest());
        $controller = new Controllers\UsersController();
        $this->setExpectedException('ErrorException');
        $controller->updateLocations(0, $request);
    }

    public function testDestroyYourself()
    {
        $user = User::find(2);
        $this->be($user);
        $this->visit('users/2/delete');
        $this->see('Delete User');
        $this->seePageIs('users/2/delete');
        $this->assertViewHas('object', $this->getDeleteFormUserObject(2));
        $this->press('Confirm');
        $this->see('You cannot delete yourself');
    }

    public function testDeleteForm()
    {
        $this->visit('users/2/delete');
        $this->see('Delete User');
        $this->seePageIs('users/2/delete');
        $this->assertViewHas('object', $this->getDeleteFormUserObject(2));
        $this->press('Confirm');
        $this->see('User was successfully deleted');
    }

    /**
     * @author EB
     * @param string $email
     * @param string $name
     * @param string $password
     * @param int $merchant_id
     * @return array
     */
    private function getPostUserData(
        $email = 'test@dev.com',
        $merchant_id = 1,
        $name = 'Test',
        $password = 'password'
    ) {
        return [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'merchant_id' => $merchant_id,
        ];
    }

    /**
     * @author EB
     * @param int $id
     * @return User
     */
    private function getDeleteFormUserObject($id = 1)
    {
        $user = $this->callPrivateMethodOnClass(
            'fetchUserById',
            [$id],
            Controllers\UsersController::class
        );

        $user->type = 'users';
        $user->controller = 'Users';

        return $user;
    }
}
