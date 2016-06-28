<?php

namespace App\Basket\Email;

use App\Basket\Application;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use WNowicki\Generic\Logger\PsrLoggerTrait;

class EmailApplicationService
{
    use PsrLoggerTrait;

    private $logger;

    public function __construct(LoggerInterface $loggerInterface = null)
    {
        $this->logger = $loggerInterface;
    }

    public function sendDefaultApplicationEmail(Request $request, Application $application)
    {
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
