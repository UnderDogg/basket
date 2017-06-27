<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket;

use App\Exceptions\Exception;
use App\Helpers\NotificationPreferences;
use App\Http\Traits\FlagTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Location Model
 *
 * @author WN
 * @property int    $id
 * @property string $reference
 * @property int    $installation_id
 * @property bool   $active
 * @property string $name
 * @property string $email
 * @property string $address
 * @property        $created_at
 * @property        $updated_at
 * @property NotificationPreferences $notifications
 * @property Installation $installation
 * @package App\Basket
 */
class Location extends Model
{
    use FlagTrait;

    protected $table = 'locations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'installation_id',
        'name',
        'email',
        'address',
        'notifications',
    ];

    /**
     * Return the fields addressed by a flagged type:
     * ['field_name' => Object::class]
     * @author GK
     * @return array
     */
    protected function getFlagFields()
    {
        return [
            'notifications' => NotificationPreferences::class
        ];
    }

    /**
     * @author GK
     * @param $value
     */
    protected function setNotificationsAttribute($value)
    {
        $this->setFlagAttribute('notifications', $value);
    }

    /**
     * Get the installation record for the application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function installation()
    {
        return $this->belongsTo('App\Basket\Installation');
    }

    /**
     * Checks related installation, makes sure active is true
     *
     * @author EB
     * @return $this
     * @throws Exception
     */
    public function activate()
    {
        if (!$this->exists()) {
            throw new Exception('Trying to deactivate none existing Location');
        }

        if (!$this->installation()->get()[0]->active) {
            throw new Exception('Can\'t activate Location because Installation is not active.');
        }

        $this->active = true;

        if ($this->save()) {
            return $this;
        }
        throw new Exception('Problem saving details');
    }

    /**
     * Sets active to false on locations
     *
     * @author EB
     * @return $this
     * @throws Exception
     */
    public function deactivate()
    {
        if (!$this->exists()) {
            throw new Exception('Trying to activate none existing Location');
        }
        $this->active = false;

        if ($this->save()) {
            return $this;
        }
        throw new Exception('Problem saving details');
    }

    /**
     * @author EB
     * @return array
     */
    public function getEmails()
    {
        return explode(',', $this->email);
    }

    /**
     * @author GK
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        $attributes = $this->forceFillFlags($attributes);

        return parent::fill($attributes);
    }
}
