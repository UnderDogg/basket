<?php

namespace App\Basket\Email;

use App\Basket\Application;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Class EmailApplicationService
 *
 * @author EB
 * @package App\Basket\Email
 */
class EmailApplicationService
{
    use PsrLoggerTrait;

    private $logger;

    public function __construct(LoggerInterface $loggerInterface = null)
    {
        $this->logger = $loggerInterface;
    }

    /**
     * @author EB
     * @param Application $application
     * @param array $data
     * @internal param Request $request
     */
    public function sendDefaultApplicationEmail(Application $application, array $data)
    {
        // Boilerplate - Requires Template Engine
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
