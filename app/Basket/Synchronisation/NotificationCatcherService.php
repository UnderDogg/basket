<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Synchronisation;

use Psr\Log\LoggerInterface;
use App\Exceptions\Exception;

/**
 * Notification Catcher Service
 *
 * @author WN
 * @package App\Basket\Synchronisation
 */
class NotificationCatcherService extends AbstractSynchronisationService
{
    private $applicationSynchronisationServices;

    public function __construct(
        ApplicationSynchronisationService $applicationSynchronisationService,
        LoggerInterface $logger = null
    ) {
        parent::__construct($logger);

        $this->applicationSynchronisationServices = $applicationSynchronisationService;
    }

    /**
     * @author WN
     * @param int $application External ID
     * @param string $installation External ID
     * @return \App\Basket\Application
     * @throws Exception
     */
    public function catchNotification($application, $installation)
    {
        $this->logInfo(
            'NotificationCatcherService: Received notification for application[ext' . $application .
            '] on gate for installation[' . $installation . ']'
        );

        try {
            $app = $this->applicationSynchronisationServices->linkApplication($application, $installation);

        } catch (\Exception $e) {

            $this->logError('NotificationCatcherService: Application[ext' . $application .
                '] can not be fetched or linked:' . $e->getMessage());
            throw new Exception('NotificationCatcherService: Application can not be fetched or linked');
        }

        try {
            $app = $this->applicationSynchronisationServices->synchroniseApplication($app->id);
        } catch (\Exception $e) {

            $this->logError('NotificationCatcherService: Application[ext' . $application .
                '] can not be synced: ' . $e->getMessage());
            throw new Exception('NotificationCatcherService: Application can not be synced');
        }

        return $app;
    }
}
