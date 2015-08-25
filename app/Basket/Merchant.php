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

/**
 * Merchant Model
 *
 * @author WN
 * @property int    $id
 * @property string $name
 * @property string $token
 * @property bool   $linked
 * @property bool   $active
 * @property string $ext_company_name
 * @property string $ext_address
 * @property string $ext_processing_days
 * @property string $ext_minimum_amount_settled
 * @property string $ext_address_on_agreements
 * @package App\Basket
 */
class Merchant extends Model
{
    protected $table = 'merchants';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
        'linked',
        'ext_company_name',
        'ext_minimum_amount_settled',
        'ext_address',
        'ext_processing_days',
        'ext_address_on_agreements',
        'updated_at',
        'created_at',
    ];

    /**
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installations()
    {
        return $this->hasMany('App\Basket\Installation');
    }

    /**
     * @author EB
     * @return $this
     * @throws Exception
     */
    public function deactivate()
    {
        if(!$this->exists) {
            throw new Exception('Trying to deactivate none existing Merchant');
        }
        $this->active = false;

        if ($this->save()) {
            foreach($this->installations()->get() as $inst) {
                $inst->deactivate();
            }
            return $this;
        }

        throw new Exception('Problem saving details');
    }

    /**
     * @author EB
     * @return $this
     * @throws Exception
     */
    public function activate()
    {
        if(!$this->exists) {
            throw new Exception('Trying to activate none existing Merchant');
        }
        $this->active = true;

        if($this->save()) {
            return $this;
        }

        throw new Exception('Problem saving details');
    }
}
