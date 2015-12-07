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
        Artisan::call('db:seed', ['--class' => 'DBSeeder']);

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
}
