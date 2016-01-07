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

    /**
     * @author EB
     */
    public function testFetchModelByIdWithInvalidData()
    {
        try {
            $this->callProtectedMethodOnAbstractClass(
                'fetchModelById',
                [
                    new User(),
                    0,
                    'User',
                    '/users',
                ]
            );
        } catch (RedirectException $e) {
            $this->assertEquals('Could not found User with ID:0', $e->getError());
            $this->assertEquals('/users', $e->getTarget());
        }
    }

    /**
     * @author EB
     */
    public function testDestroyModel()
    {
        $this->callProtectedMethodOnAbstractClass(
            'destroyModel',
            [
                new User(),
                1,
                'Location',
                '/locations',
            ]
        );

        $this->assertSessionHas('messages', ['success' => 'Location was successfully deleted']);
    }

    /**
     * @author EB
     */
    public function testDestroyModelWithInvalidData()
    {
        try {
            $this->callProtectedMethodOnAbstractClass(
                'destroyModel',
                [
                    new User(),
                    0,
                    'Location',
                    '/locations',
                ]
            );
        } catch (RedirectException $e) {
            $this->assertEquals('Deletion of this record did not complete successfully', $e->getError());
            $this->assertEquals('/locations', $e->getTarget());
        }
    }

    /**
     * @author EB
     */
    public function testUpdateModel()
    {
        $details = [
            'name' => 'Tester',
            'email' => 'tester@test.com',
        ];
        $request = new \Illuminate\Http\Request($details);

        $this->callProtectedMethodOnAbstractClass(
            'updateModel',
            [
                new User(),
                1,
                'User',
                '/user',
                $request
            ]
        );
        $new = $this->callProtectedMethodOnAbstractClass(
            'fetchModelById',
            [
                new User(),
                1,
                'User',
                '/user',
            ]
        )->first()->toArray();

        foreach($details as $k => $v) {
            $this->assertEquals($details[$k], $new[$k]);
        }

        $this->assertSessionHas('messages', ['success' => 'User details were successfully updated']);
    }

    /**
     * @author EB
     */
    public function testUpdateActiveField()
    {
        $merchant = new Merchant();
        $merchant = $merchant->find(1);

        $old = $merchant->first()->toArray();
        $new = $this->callProtectedMethodOnAbstractClass(
            'updateActiveField',
            [
                $merchant,
                1,
            ]
        )->first()->toArray();

        $this->assertEquals(0, $old['active']);
        $this->assertNotEquals(1, $old['active']);
        $this->assertEquals(1, $new['active']);
        $this->assertNotEquals(0, $new['active']);
    }
}
