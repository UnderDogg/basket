<?php

namespace App\Basket\Email;

use App\Basket\Application;
use App\Http\Controllers\TemplatesController;
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

    /**
     * @author EB
     * @param Request $request
     * @param Application $application
     * @return bool
     */
    public function sendDefaultApplicationEmail(Request $request, Application $application)
    {
        $template = TemplatesController::fetchDefaultTemplateForInstallation($application->installation);
        $data = EmailTemplateEngine::formatRequestForEmail($request);

        $txt = \DbView::make($template)->field('html')->with([])->render();

        \Mail::send('emails.applications.blank', ['content' => $txt], function($message) use ($request) {
            $message->to($request->get('email'))
                ->subject($request->get('subject'));
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
