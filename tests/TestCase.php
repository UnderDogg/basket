<?php

use App\Http\Controllers\Controller;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

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

        $obj = $this->getMock($class);

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
     * @author EB
     * @param string $request
     * @param array $params
     * @return \App\Http\Requests\Request
     */
    protected function createRequestForTest(array $params = [], $request = null)
    {
        if (!is_null($request)) {
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
