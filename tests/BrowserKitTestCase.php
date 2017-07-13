<?php

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Console\Kernel;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class BrowserKitTestCase extends BaseTestCase
{
    /**
     * The base URL of the application.
     *
     * @var string
     */
    public $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Calls a protected method on an abstract class
     *
     * @author EB
     * @param $class
     * @param $method
     * @param $params
     * @return mixed
     */
    protected function callProtectedMethodOnAbstractClass($method, $params, $class = Controller::class)
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        $obj = $this->getMockForAbstractClass($class);

        return $method->invokeArgs($obj, $params);
    }

    /**
     * Call a private method on a class
     *
     * @author EB
     * @param $method
     * @param $params
     * @param $class
     * @return mixed
     */
    protected function callPrivateMethodOnClass($method, $params, $class = Controller::class)
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        $obj = $this->createMock($class);

        return $method->invokeArgs($obj, $params);
    }

    /**
     * Appends a method type onto a request
     *
     * @author EB
     * @param array $request
     * @param string $method
     * @return array
     */
    protected function addMethodOntoRequest(array $request = [], $method = 'patch')
    {
        $request['_method'] = $method;
        return $request;
    }

    /**
     * Creates a fake request for testing
     *
     * @author EB, JH
     * @param array $params
     * @param string $request
     * @return mixed
     */
    protected function createRequestForTest(array $params = [], $request = '')
    {
        if (!empty($request)) {
            $requestClass = 'App\Http\Requests\\' . $request;
        } else {
            $requestClass = '\Illuminate\Http\Request';
        }

        return new $requestClass($params);
    }

    /**
     * Creates an application using the ModelFactory
     *
     * @author EB
     * @return \App\Basket\Application
     */
    protected function createApplicationForTest()
    {
        return factory(\App\Basket\Application::class)->create();
    }
}
