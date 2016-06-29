<?php

namespace App\Basket;

use App\Exceptions\Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationEvent
 *
 * @property int $id
 * @property int $application_id
 * @property int $type
 * @property string $description
 * @property string $metadata
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Basket
 * @author SL
 */
class ApplicationEvent extends Model
{
    protected $table = 'application_events';

    const TYPE_CUSTOM = 1;
    const TYPE_INITIALISED = 2;
    const TYPE_LINK = 4;
    const TYPE_EMAIL = 8;
    const TYPE_INSTORE = 16;

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
    public function getTypeDescriptionMap()
    {
        return [
            self::TYPE_CUSTOM =>      'Custom Event.',
            self::TYPE_INITIALISED => 'Application Initialised',
            self::TYPE_LINK =>        'Application Link Created in Retailer Back Office',
            self::TYPE_EMAIL =>       'Application Email Sent',
            self::TYPE_INSTORE =>     'Instore Application Started',
        ];
    }
}
