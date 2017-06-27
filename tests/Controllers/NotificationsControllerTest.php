<?php

use App\Basket\Application;
use App\Basket\Location;
use App\Basket\Notifications\LocationNotificationService;
use App\Basket\Synchronisation\NotificationCatcherService;
use App\Helpers\NotificationPreferences;
use App\Http\Controllers\NotificationsController;
use Illuminate\Http\Request;

/**
 * Class NotificationsControllerTest
 *
 * @author GK
 */
class NotificationsControllerTest extends TestCase
{
    /**
     * @author GK
     */
    public function testCatchNotificationOnConvertedWhenOn()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::CONVERTED)
            ->willReturn(true);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->once())->method('convertedNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_CONVERTED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     */
    public function testCatchNotificationOnConvertedWhenOff()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::CONVERTED)
            ->willReturn(false);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->never())->method('convertedNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_CONVERTED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     */
    public function testCatchNotificationOnPreDeclinedWhenOn()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::DECLINED)
            ->willReturn(true);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->once())->method('declinedNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_PRE_DECLINED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     */
    public function testCatchNotificationOnPreDeclinedWhenOff()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::DECLINED)
            ->willReturn(false);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->never())->method('declinedNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_PRE_DECLINED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     */
    public function testCatchNotificationOnDeclinedWhenOn()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::DECLINED)
            ->willReturn(true);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->once())->method('declinedNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_DECLINED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     */
    public function testCatchNotificationOnDeclinedWhenOff()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::DECLINED)
            ->willReturn(false);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->never())->method('declinedNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_DECLINED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     */
    public function testCatchNotificationOnReferredWhenOn()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::REFERRED)
            ->willReturn(true);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->once())->method('referredNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_REFERRED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     */
    public function testCatchNotificationOnReferredWhenOff()
    {
        $installation = 'TestInstallation';

        $mockNotifications = $this->getMockBuilder(NotificationPreferences::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotifications->expects($this->once())
            ->method('has')
            ->with(NotificationPreferences::REFERRED)
            ->willReturn(false);

        $mockLocation = $this->getMock(Location::class);
        $mockLocation->expects($this->once())->method('__get')->with('notifications')->willReturn($mockNotifications);

        $mockApplication = new Application();
        $mockApplication->location = $mockLocation;

        $mockNotificationCatcher = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        $mockNotificationCatcher->expects($this->once())->method('catchNotification')->willReturn($mockApplication);

        $mockLocationNotificationService = $this->getMockBuilder(
            \App\Basket\Notifications\EmailLocationNotificationService::class
        )->disableOriginalConstructor()->getMock();
        $mockLocationNotificationService->expects($this->never())->method('referredNotification');

        $notificationsController = $this->getNewNotificationsController(
            $mockNotificationCatcher,
            $mockLocationNotificationService
        );

        $request = $this->getMock(Request::class);

        $request->expects($this->any())->method('json')
            ->willReturn(NotificationsController::STATUS_REFERRED);

        $notificationsController->catchNotification($installation, $request);
    }

    /**
     * @author GK
     * @param NotificationCatcherService|null $notificationCatcherService
     * @param LocationNotificationService|null $locationNotificationService
     * @return NotificationsController
     */
    private function getNewNotificationsController(
        NotificationCatcherService $notificationCatcherService = null,
        LocationNotificationService $locationNotificationService = null
    ) {
        if (is_null($notificationCatcherService)) {
            $notificationCatcherService = $this->getMockBuilder(NotificationCatcherService::class)
            ->disableOriginalConstructor()->getMock();
        }

        if (is_null($locationNotificationService)) {
            $locationNotificationService = $this->getMockBuilder(LocationNotificationService::class)
                ->disableOriginalConstructor()->getMock();
        }

        return new NotificationsController($notificationCatcherService, $locationNotificationService);
    }
}
