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
        $this->typeEditDetails('', 'NotAnEmail');

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
    public function testEditRequiredFields()
    {
        $this->typeEditDetails('', '');

        $this->see('The name field is required');
        $this->see('The email field is required');
    }

    /**
     * @author EB
     */
    public function testEditDetails()
    {
        $this->typeEditDetails('Developer', 'develop@paybreak.com');

        $this->see('Your details have successfully been changed');
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
     * @author EB
     */
    public function testOldPasswordStored()
    {
        $this->typeEditPasswordDetails('test', 'pass', 'pass');
        $messages = $this->app['session.store']->get('messages');
        $this->assertEquals($messages, [
            'error' => 'Old password must match stored password'
        ]);
    }

    /**
     * @author EB
     */
    public function testEditPasswordWithCorrectData()
    {
        $this->typeEditPasswordDetails('password', 'new-password', 'new-password');
        $messages = $this->app['session.store']->get('messages');
        $this->assertEquals($messages, [
            'success' => 'Your password has successfully been changed'
        ]);
        $this->seePageIs('account/edit');
        $this->see('Your password has successfully been changed');
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

    /**
     * Used to test all validation on edit details
     *
     * @author EB
     * @param $name
     * @param $email
     */
    private function typeEditDetails($name, $email)
    {
        $this->visit('account/edit')
            ->seeStatusCode(200)
            ->type($name, 'name')
            ->type($email, 'email')
            ->press('Update details')
            ->seePageIs('/account/edit')
            ->assertViewHas('user');
    }
}
