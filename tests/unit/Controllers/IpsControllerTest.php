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

class IpsControllerTest extends BrowserKitTestCase
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
     * Test the ip address page
     *
     * @author EA, EB
     */
    public function testIndexPage()
    {
        $mockApiClient = $this->createMock('PayBreak\Sdk\ApiClient\ProviderApiClient');
        $mockApiClient->expects($this->any())->method('get')->willReturn([]);

        $mock = $this->createMock('PayBreak\Sdk\ApiClient\ApiClientFactoryInterface');
        $mock->expects($this->any())->method('makeApiClient')->willReturn($mockApiClient);

        $ipsGateway = new \PayBreak\Sdk\Gateways\IpsGateway($mock);
        $this->app->instance('PayBreak\Sdk\Gateways\IpsGateway', $ipsGateway);

        $this->visit('/merchants/1/ips')
            ->seeStatusCode(200);
    }
}
