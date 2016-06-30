<?php

namespace App\Basket\Email;

use App\Basket\Application;
use App\Basket\Template;
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
     * @param Template $template
     * @param array $data
     * @return bool
     */
    public function sendDefaultApplicationEmail(Application $application, Template $template, array $data)
    {
        $txt = \DbView::make($template)->field('html')->with($data)->render();

        \Mail::send('emails.applications.blank', ['content' => $txt], function ($message) use ($data) {
            $message->to($data['customer_email'])
                ->subject($data['email_subject']);
        });

        $this->logInfo('EmailApplicationService: Application Email sent for Application[' . $application->id . ']');

        return true;
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
