<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Email;

use App\Basket\Application;
use App\Basket\Template;
use Illuminate\Mail\Message;
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
        $txt = $this->getView($template, $data);

        $subject = (
            isset($data['email_subject']) &&
            !is_null($data['email_subject']) ?
                $data['email_subject'] :
                env('EMAIL_TEMPLATE_DEFAULT_SUBJECT', 'Your afforditNOW Finance Application')
        );

        $replyTo = (
            isset($data['email_reply_to']) &&
            !is_null($data['email_reply_to']) ?
                $data['email_reply_to'] :
                env('EMAIL_TEMPLATE_DEFAULT_REPLY_TO')
        );

        $fromName = (
            isset($data['email_from_name']) &&
            !is_null($data['email_from_name']) ?
                $data['email_from_name'] :
                env('EMAIL_TEMPLATE_DEFAULT_FROM_NAME', 'afforditNOW Finance')
        );

        \Mail::send(
            'emails.applications.blank',
            ['content' => $txt],
            function (Message $message) use ($data, $subject, $replyTo, $fromName) {
                $message->to($data['email_recipient'])
                        ->subject($subject)
                        ->replyTo($replyTo)
                        ->from(
                            env('MAIL_FROM'),
                            $fromName
                        );
            }
        );

        $this->logInfo('EmailApplicationService: Application Email sent for Application[' . $application->id . ']');

        return true;
    }

    /**
     * @author SL
     * @param Template $template
     * @param array $data
     * @return string
     */
    public function getView(Template $template, array $data)
    {
        return \DbView::make($template)->field('html')->with($data)->render();
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
