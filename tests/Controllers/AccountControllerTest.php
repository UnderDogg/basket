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
use Symfony\Component\Console\Tests\Input;

class AccountControllerTest extends TestCase
{
    /**
     * @author WN
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
     * @author WN
     */
    public function testShow()
    {
        // Test page gives 200 response
        $this->visit('/account')
            ->seeStatusCode(200);
    }

    /**
     * @author WN
     */
    public function testEdit()
    {
        // Test page gives 200 response
        $this->visit('/account/edit')
            ->seeStatusCode(200);
    }

    /**
     * @author EB
     */
    public function testEditWithIncorrectData()
    {
        $this->visit('account/edit')
            ->seeStatusCode(200)
            ->type('', 'name')
            ->type('NotAnEmail', 'email')
            ->press('Update details')
            ->seePageIs('/account/edit')
            ->assertViewHas('user');

        $this->assertSessionHasErrors(
            [
                'name' => 'The name field is required.',
                'email' => 'The email must be a valid email address.',
            ]
        );
    }

    /**
     * @author EB
     */
    public function testNewPasswordConfirmation()
    {
        $this->typeEditPasswordDetails('password', 'password', 'testing');

        $this->assertSessionHasErrors(
            [
                'new_password',
                'new_password_confirmation',
            ],
            [
                'The new password confirmation does not match.',
                'The new password and old password must be different.',
                'The new password confirmation and new password must match.',
            ]
        );
    }

    /**
     * @author EB
     */
    public function testOldPasswordRequired()
    {
        $this->typeEditPasswordDetails('', 'test', 'test');
        $this->assertSessionHasErrors('old_password', 'The old password field is required.');

    }

    /**
     * @author EB
     */
    public function testEditPasswordRequired()
    {
        $this->typeEditPasswordDetails('', '', '');
        $this->assertSessionHasErrors(
            [
                'old_password',
                'new_password',
                'new_password_confirmation',
            ],
            [
                'The old password field is required.',
                'The new password field is required.',
                'The new password confirmation field is required.',
            ]
        );
    }

    /**
     * Used to test all validation on edit password details
     *
     * @author EB
     * @param string $old
     * @param string $new
     * @param string $confirm
     */
    private function typeEditPasswordDetails($old, $new, $confirm)
    {
        $this->visit('account/edit')
            ->seeStatusCode(200)
            ->type($old, 'old_password')
            ->type($new, 'new_password')
            ->type($confirm, 'new_password_confirmation')
            ->press('Change password')
            ->seePageIs('/account/edit')
            ->assertViewHas('user');
    }
}
