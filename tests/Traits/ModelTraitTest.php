<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Basket\Merchant;
use App\Exceptions\RedirectException;
use App\User;
use App\Http\Controllers;

class ModelTraitTest extends TestCase
{
    /**
     * using MerchantTableSeeder so we can do checks to make sure that the user is allowed for different merchants
     *
     * @author EB
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DevSeeder']);
        Artisan::call('db:seed', ['--class' => 'MerchantTableSeeder']);

        $user = User::find(1);
        $this->be($user);
    }

    /**
     * @author EB
     */
    public function testFetchModelById()
    {
        $test = $this->callProtectedMethodOnAbstractClass(
            'fetchModelById',
            [
                new User(),
                1,
                'User',
                '/users',
            ]
        );

        $this->assertInstanceOf(User::class, $test);
    }
}
