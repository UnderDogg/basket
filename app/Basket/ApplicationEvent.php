<?php

namespace App\Basket;

use App\Exceptions\Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Class ApplicationEvent
 *
 * @property int $id
 * @property int $application_id
 * @property int $type
 * @property string $description
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User|null $user
 *
 * @package App\Basket
 * @author SL
 */
class ApplicationEvent extends Model
{
    protected $table = 'application_events';

    const TYPE_CUSTOM = 0;
    const TYPE_NOTIFICATION = 1;
    const TYPE_NOTIFICATION_INITIALISED = 3; // 2 + TYPE_NOTIFICATION
    const TYPE_RESUME_SENT = 4;
    const TYPE_RESUME_LINK = 12; // 8 + TYPE_RESUME_SENT
    const TYPE_RESUME_EMAIL = 20; // 16 + TYPE_RESUME_SENT
    const TYPE_RESUME_INSTORE = 36; // 32 + TYPE_RESUME_SENT

    /**
     * @author SL
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application()
    {
        return $this->belongsTo(\App\Basket\Application::class);
    }

    /**
     * @author SL
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Get the default description from the type, which should be from the group of constants of this class.
     *
     * @author SL
     * @param int $type
     * @return string
     * @throws Exception
     */
    public function getDefaultDescription($type)
    {
        $descriptions = $this->getTypeDescriptionMap();

        if (array_key_exists($type, $descriptions)) {
            return $descriptions[$type];
        }

        throw new Exception(
            'The given event type does not exist, therefore a default description cannot be returned.'
        );
    }

    /**
     * Get map of descriptions of events.
     *
     * @author SL
     * @return array
     */
    private static function getTypeDescriptionMap()
    {
        return [
            self::TYPE_CUSTOM                   => 'Custom Event.',
            self::TYPE_NOTIFICATION_INITIALISED => '(Notification Received) - Application Initialised',
            self::TYPE_RESUME_LINK              =>
                '(Application Resume) - Application Link Created in Retailer Back Office',
            self::TYPE_RESUME_EMAIL             => '(Application Resume) - Application Email Sent',
            self::TYPE_RESUME_INSTORE           => '(Application Resume) - Instore Application Started',
        ];
    }

    /**
     * @author SL
     * @param $type
     * @return bool
     * @throws Exception
     */
    public static function validateEventType($type)
    {
        if (array_key_exists($type, self::getTypeDescriptionMap())) {
            return true;
        }

        throw new Exception('Event Type [' . $type . '] does not exist');
    }
}
