<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use \App\Gateways\ProviderGateway;

/**
 * Class ProviderGatewayTest
 *
 * @author WN
 */
class ProviderGatewayTest extends TestCase
{
    public function testMake()
    {
        $this->assertInstanceOf('App\Gateways\ProviderGateway', ProviderGateway::make('http://httpbin.org/', 'testToken'));
    }
}
