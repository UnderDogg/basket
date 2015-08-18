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
 * @property true   $active
 * @property true   $linked
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
