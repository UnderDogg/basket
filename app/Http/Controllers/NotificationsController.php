<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Http\Controllers;

use App\Basket\Location;
use App\Basket\Notifications\LocationNotificationService;
use App\Basket\Synchronisation\NotificationCatcherService;
use Illuminate\Http\Request;

/**
 * Notifications Controller
 *
 * @author WN
 * @package App\Http\Controllers
 */
class NotificationsController extends Controller
{
    const STATUS_CONVERTED = 'converted';
    const STATUS_PRE_DECLINED = 'pre_declined';
    const STATUS_DECLINED = 'declined';
    const STATUS_REFERRED = 'referred';

    private $notificationCatcherService;
    private $locationNotificationService;

    public function __construct(
        NotificationCatcherService $notificationCatcherService,
        LocationNotificationService $locationNotificationService
    ) {
        $this->notificationCatcherService = $notificationCatcherService;
        $this->locationNotificationService = $locationNotificationService;
    }

    /**
     * @author WN
     * @param $installation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function catchNotification($installation, Request $request)
    {
        try {
            $application = $this->notificationCatcherService
                ->catchNotification($request->json('application'), $installation);

            if ($application->location !== null) {
                $this->processNotification($application, $request);
            }

            return response()->json(
                ['local_id' => $application->id, 'current_status' => $application->ext_current_status],
                200
            );
        } catch (\Exception $e) {
            $this->logError('CatchNotification: Failed with message: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * @author EB
     * @param $installation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function catchSynchronisationNotification($installation, Request $request)
    {
        try {
            $this->notificationCatcherService->catchSynchronisationNotification($installation);
            return response()->json(
                ['message' => 'Installation [' . $installation . '] successfully synchronised'],
                200
            );
        } catch (\Exception $e) {
            $this->logError('CatchSynchronisationNotification: Failed with message: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * @author GK
     * @param \App\Basket\Application $application
     * @param Request $request
     */
    private function processNotification($application, Request $request)
    {
        switch ($request->json('new_status')) {
            case self::STATUS_CONVERTED:
                if ($application->location->notifications->contains(Location::NOTIFICATIONS_CONVERTED)) {
                    $this->locationNotificationService->convertedNotification($application, $application->location);
                }
                break;
            case self::STATUS_PRE_DECLINED:
            case self::STATUS_DECLINED:
                if ($application->location->notifications->contains(Location::NOTIFICATIONS_DECLINED)) {
                    $this->locationNotificationService->declinedNotification($application, $application->location);
                }
                break;
            case self::STATUS_REFERRED:
                if ($application->location->notifications->contains(Location::NOTIFICATIONS_REFERRED)) {
                    $this->locationNotificationService->referredNotification($application, $application->location);
                }
        }
    }
}
