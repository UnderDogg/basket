<?php

namespace App\Basket\ApplicationEvent;

use App\Basket\Application;
use App\Basket\ApplicationEvent;
use App\Exceptions\Exception;

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
     * @param array       $metadata
     * @param null        $description
     *
     * @throws Exception
     */
    public static function appendEvent(Application $application, $type, $metadata = [], $description = null)
    {
        self::validateEventType($type);

        $event = new ApplicationEvent();

        $event->application_id = $application->id;
        $event->metadata = json_encode($metadata);
        $event->type = $type;
        $event->description = (is_null($description) ? $event->getDefaultDescription($type) : $description);

        $event->save();
    }

    /**
     * @author SL
     * @param $type
     * @return bool
     * @throws Exception
     */
    private static function validateEventType($type)
    {
        $event = new ApplicationEvent();

        if (array_key_exists($type, $event->getTypeDescriptionMap())) {

            return true;
        }

        throw new Exception('Event Type [' . $type . '] does not exist');
    }
}
