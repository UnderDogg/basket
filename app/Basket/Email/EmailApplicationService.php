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

        $subject = $this->getDefaultForApplicationEmail(
            $data,
            'email_subject',
            'EMAIL_TEMPLATE_DEFAULT_SUBJECT',
            'Your afforditNOW Finance Application'
        );

        $replyTo = $this->getDefaultForApplicationEmail($data, 'email_reply_to', 'EMAIL_TEMPLATE_DEFAULT_REPLY_TO');

        $fromName = $this->getDefaultForApplicationEmail(
            $data,
            'email_from_name',
            'EMAIL_TEMPLATE_DEFAULT_FROM_NAME',
            'afforditNOW Finance'
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
     * @author EB
     * @param array $data
     * @param string $field
     * @param string $envKey
     * @param string|null $default
     * @return mixed
     */
    private function getDefaultForApplicationEmail($data, $field, $envKey, $default = null)
    {
        return (isset($data[$field]) && !is_null($data[$field]) ? $data[$field] : env($envKey, $default));
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
