<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Notifications;

use App\Basket\Application;
use App\Basket\Location;
use Psr\Log\LoggerInterface;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Email Location Notification Service
 *
 * @author WN
 * @package App\Basket\Notifications
 */
class EmailLocationNotificationService implements LocationNotificationService
{
    use PsrLoggerTrait;

    const CONVERTED = 'Converted';
    const DECLINED = 'Declined';
    const REFERRED = 'Referred';

    private $logger;

    /**
     * EmailLocationNotificationService constructor.
     *
     * @author ??
     * @param LoggerInterface|null $loggerInterface
     */
    public function __construct(LoggerInterface $loggerInterface = null)
    {
        $this->logger = $loggerInterface;
    }

    /**
     * @author WN, GK
     * @param Application $application
     * @param Location $location
     * @return bool
     */
    public function convertedNotification(Application $application, Location $location)
    {
        return $this->sendNotification(
            $application,
            $location,
            self::CONVERTED,
            'Customer Finance Application ' . $application->ext_id .' has been Approved'
        );
    }

    /**
     * @author GK
     * @param Application $application
     * @param Location $location
     * @return bool
     */
    public function declinedNotification(Application $application, Location $location)
    {
        return $this->sendNotification(
            $application,
            $location,
            self::DECLINED,
            'Customer Finance Application ' . $application->ext_id .' has been Declined'
        );
    }

    /**
     * @author GK
     * @param Application $application
     * @param Location $location
     * @return bool
     */
    public function referredNotification(Application $application, Location $location)
    {
        return $this->sendNotification(
            $application,
            $location,
            self::REFERRED,
            'Customer Finance Application ' . $application->ext_id .' has been Referred'
        );
    }

    /**
     * @author WN, GK
     * @param Application $application
     * @param Location $location
     * @param string $type
     * @param string $subject
     * @return bool
     */
    private function sendNotification(Application $application, Location $location, $type, $subject)
    {
        foreach ($location->getEmails() as $email) {
            \Mail::send(
                $this->getView($type),
                [
                    'application' => $application,
                    'location' => $location,
                ],
                function ($message) use ($email, $subject) {
                    $message->to($email)
                        ->subject($subject);
                }
            );
        }

        $this->logInfo('LocationNotificationService: ' . $type . ' Email sent for Application[' . $application->id . ']');

        return true;
    }

    /**
     * @author GK
     * @param string $type
     * @return string
     * @throws \Exception
     */
    private function getView($type)
    {
        switch ($type) {
            case self::CONVERTED:
                return 'emails.locations.converted';
            case self::DECLINED:
                return 'emails.locations.declined';
            case self::REFERRED:
                return 'emails.locations.referred';
            default:
                throw new \Exception('Selected notification type [' . $type . '] does not have a view assigned to it.');
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
