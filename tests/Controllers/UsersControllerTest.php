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

class UsersControllerTest extends TestCase
{
    /**
     * @author EA
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DevSeeder']);
        \Illuminate\Support\Facades\Mail::pretend(true);

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
        $this->visit('users/create');
        $this->assertViewHasAll(['user','merchants','rolesAvailable','rolesApplied']);
        $this->type('Test User', 'name');
        $this->type('test@user.com', 'email');
        $this->type('password', 'password');
        $this->select(1, 'merchant_id');
        $this->check('administrator');
        $this->press('createUserButton');
        $this->see('Users');
        $this->see('New user has been successfully created');
    }

    /**
     * @author EB
     */
    public function testCreateErrors()
    {
        $this->visit('users/create');
        $this->assertViewHasAll(['user','merchants','rolesAvailable','rolesApplied']);
        $this->see('Create a new User');
        $this->submitForm('createUserButton');
        $this->see('The name cannot be empty');
        $this->see('The name field is required');
        $this->see('The email cannot be empty');
        $this->see('The email field is required');
        $this->see('The password cannot be empty');
        $this->see('The password field is required');
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
}
