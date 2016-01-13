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
}
