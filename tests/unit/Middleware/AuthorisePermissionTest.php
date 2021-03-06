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
use App\Http\Middleware\AuthorisePermission;

/**
 * Authorise Permission Test
 *
 * @author WN
 */
class AuthorisePermissionTest extends BrowserKitTestCase
{
    /**
     * @author WN
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate:refresh');
        Artisan::call('db:seed', ['--class' => 'DBSeeder']);

        $user = User::find(1);
        $this->be($user);
    }

    public function testHandleThrowException()
    {
        $middleware = new AuthorisePermission();

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException');

        $middleware->handle(\Mockery::mock('\Illuminate\Http\Request'), function () {
        }, 'xxx');
    }

    public function testHandleThrowPass()
    {
        $middleware = new AuthorisePermission();

        $this->assertTrue(
            $middleware->handle(
                \Mockery::mock('\Illuminate\Http\Request'),
                function () {
                    return true;
                },
                'applications-view'
            )
        );
    }
}
