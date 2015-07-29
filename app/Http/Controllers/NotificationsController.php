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

use Illuminate\Http\Request;

/**
 * Notifications Controller
 *
 * @author WN
 * @package App\Http\Controllers
 */
class NotificationsController extends Controller
{
    /**
     * @author WN
     * @param $installation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function catchNotification($installation, Request $request)
    {
        /** @var \App\Basket\Synchronisation\NotificationCatcherService $service */
        $service = \App::make('App\Basket\Synchronisation\NotificationCatcherService');

        try {
            $application = $service->catchNotification($request->json('application'), $installation);

            return response()->json(
                ['local_id' => $application->id, 'current_status' => $application->ext_current_status],
                200
            );

        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
