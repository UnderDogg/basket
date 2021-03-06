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
use Illuminate\Database\Eloquent\Model;
use PayBreak\Foundation\Properties\Bitwise;

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
 * @property Bitwise $notifications
 * @property Installation $installation
 * @package App\Basket
 */
class Location extends Model
{
    protected $table = 'locations';

    const NOTIFICATIONS_CONVERTED = 1;
    const NOTIFICATIONS_DECLINED = 2;
    const NOTIFICATIONS_REFERRED = 4;

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
     * @author GK
     * @param $value
     * @return Bitwise
     */
    protected function getNotificationsAttribute($value)
    {
        return Bitwise::make($value);
    }

    /**
     * @author GK
     * @param array $value
     */
    protected function setNotificationsAttribute(array $value)
    {
        $this->attributes['notifications'] = Bitwise::makeFromArray($value)->get();
    }

    /**
     * Get the installation record for the application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function installation()
    {
        return $this->belongsTo(\App\Basket\Installation::class);
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
        if (!array_key_exists('notifications', $attributes)) {
            $attributes['notifications'] = [];
        }

        return parent::fill($attributes);
    }
}
