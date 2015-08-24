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

use Illuminate\Database\Eloquent\Model;

/**
 * Installation Model
 *
 * @author WN
 * @property int    $id
 * @property int    $merchant_id
 * @property string $name
 * @property bool   $linked
 * @property string $ext_id
 * @property string $ext_name
 * @property string $ext_return_url
 * @property string $ext_notification_url
 * @property string $ext_default_product
 * @property        $created_at
 * @property        $updated_at
 * @property Merchant $merchant
 * @property string $location_instruction
 * @property int    $validity
 * @package App\Basket
 */
class Installation extends Model
{
    protected $table = 'installations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'merchant_id',
        'active',
        'linked',
        'ext_id',
        'ext_name',
        'ext_return_url',
        'ext_notification_url',
        'ext_default_product',
        'location_instruction',
        'validity',
    ];

    /**
     * @author WN
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo('App\Basket\Merchant');
    }

    /**
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany('App\Basket\Location');
    }

    /**
     * @author EB
     * @param int $id
     * @method findOrFail(integer $id)
     */
    public function activeFalse($id)
    {
        $this->findOrFail($id)->locations()->update(['active' => 0]);
    }

    /**
     * @author EB
     * @param int $merchantId
     * @method where()
     */
    public function multiActiveFalse($merchantId)
    {
        $inst = $this->where('merchant_id','=',$merchantId)->get();
        foreach($inst as $install=>$val) {
            $this->activeFalse($val->id);
        }
    }

    /**
     * @author EB
     * @param int $id
     * @property active
     * @method findOrFail(integer $id)
     */
    public function activeTrue($id)
    {
        $merchant = $this->findOrFail($id)->merchant()->get();
        $this->active = ($merchant['0']->active == 1) ? 1 : 0;
        $this->save();
    }

    /**
     * Returning HTML for Parsed Markdown
     *
     * @author WN
     * @return string
     */
    public function getLocationInstructionAsHtml()
    {
        return ((new \Parsedown())->text(htmlspecialchars($this->location_instruction)));
    }


}
