<?php

namespace App\Basket\ApplicationEvent;

use App\Basket\Application;
use App\Basket\ApplicationEvent;
use App\Exceptions\Exception;
use App\User;

/**
 * Class ApplicationEventHelper
 *
 * @package App\Basket\ApplicationEvent
 * @author SL
 */
class ApplicationEventHelper
{
    /**
     * AppendEvent for Application
     *
     * @author SL
     *
     * @param Application $application
     * @param             $type
     * @param User|null   $user
     * @param null        $description
     *
     * @throws Exception
     */
    public static function addEvent(Application $application, $type, User $user = null, $description = null)
    {
        ApplicationEvent::validateEventType($type);

        $event = new ApplicationEvent();

        $event->application_id = $application->id;
        $event->user_id = (is_null($user) ? null : $user->id);
        $event->type = $type;
        $event->description = (is_null($description) ? $event->getDefaultDescription($type) : $description);

        $event->save();
    }

    public static function getEvents(Application $application)
    {
        return $application->applicationEvents()->orderByRaw('created_at ASC, id ASC')->get();
    }
}
