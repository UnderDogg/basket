<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Gateways;

use \App\Gateways\ProviderApiClient;

/**
 * Class ProviderApiClientTest
 *
 * @author WN
 */
class ProviderApiClientTest extends \TestCase
{
    /**
     * @author WN
     */
    public function testMake()
    {
        $this->assertInstanceOf('App\Gateways\ProviderApiClient', ProviderApiClient::make('http://httpbin.org/', 'testToken'));
    }

    /**
     * @author WN
     */
    public function testGet()
    {
        $api = ProviderApiClient::make('http://httpbin.org/', 'testToken');

        $this->assertInternalType('array', $api->get('get'));
    }

    /**
     * @author WN
     */
    public function testTokenHeader()
    {
        $api = ProviderApiClient::make('http://httpbin.org/', 'testToken');

        $response = $api->get('get');

        $this->assertArrayHasKey('headers', $response);

        $this->assertContains('ApiToken token="testToken"', $response['headers']);
    }

    /**
     * @author WN
     */
    public function testQueryIsWorking()
    {
        $api = ProviderApiClient::make('http://httpbin.org/', 'testToken');

        $response = $api->get('get', ['x' => 'y', 'z' => 'a']);

        $this->assertArrayHasKey('args', $response);

        $this->assertCount(2, $response['args']);
    }

    /**
     * @author WN
     */
    public function testPost()
    {
        $api = ProviderApiClient::make('http://httpbin.org/', 'testToken');

        $this->assertInternalType('array', $api->post('post'));
    }

    /**
     * @author WN
     */
    public function testDelete()
    {
        $api = ProviderApiClient::make('http://httpbin.org/', 'testToken');

        $this->assertInternalType('array', $api->delete('delete'));
    }

    /**
     * @author WN
     */
    public function testPut()
    {
        $api = ProviderApiClient::make('http://httpbin.org/', 'testToken');

        $this->assertInternalType('array', $api->put('put'));
    }

    /**
     * @author WN
     */
    public function testPatch()
    {
        $api = ProviderApiClient::make('http://httpbin.org/', 'testToken');

        $this->assertInternalType('array', $api->patch('patch'));
    }
}
