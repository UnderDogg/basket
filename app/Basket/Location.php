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
 * @property int    $email_notifications
 * @property Installation $installation
 * @package App\Basket
 */
class Location extends Model
{
    protected $table = 'locations';

    const EMAIL_CONVERTED = 1;
    const EMAIL_DECLINED = 2;
    const EMAIL_REFERRED = 4;

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
        'email_notifications',
    ];

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
     * @return bool
     */
    public function getConvertedEmailSetting()
    {
        return $this->getEmailSettingFlag(self::EMAIL_CONVERTED);
    }

    /**
     * @author GK
     * @param $bool
     * @return $this
     */
    public function setConvertedEmailSetting($bool)
    {
        $this->setEmailSettingFlag(self::EMAIL_CONVERTED, $bool);

        return $this;
    }

    /**
     * @author GK
     * @return bool
     */
    public function getDeclinedEmailSetting()
    {
        return $this->getEmailSettingFlag(self::EMAIL_DECLINED);
    }

    /**
     * @author GK
     * @param $bool
     * @return $this
     */
    public function setDeclinedEmailSetting($bool)
    {
        $this->setEmailSettingFlag(self::EMAIL_DECLINED, $bool);

        return $this;
    }

    /**
     * @author GK
     * @return bool
     */
    public function getReferredEmailSetting()
    {
        return $this->getEmailSettingFlag(self::EMAIL_REFERRED);
    }

    /**
     * @author GK
     * @param $bool
     * @return $this
     */
    public function setReferredEmailSetting($bool)
    {
        $this->setEmailSettingFlag(self::EMAIL_REFERRED, $bool);

        return $this;
    }

    /**
     * @author GK
     * @param $flag
     * @return bool
     */
    private function getEmailSettingFlag($flag)
    {
        return Bitwise::make($this->email_notifications)->contains($flag);
    }

    /**
     * @author GK
     * @param $flag
     * @param $bool
     * @return int
     */
    private function setEmailSettingFlag($flag, $bool)
    {
        if ($bool) {
            return $this->email_notifications = Bitwise::make($this->email_notifications)->apply($flag);
        } else {
            return $this->email_notifications = Bitwise::make($this->email_notifications)->remove($flag);
        }
    }
}
