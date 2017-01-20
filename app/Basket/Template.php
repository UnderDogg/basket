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
 * Template Model
 *
 * @author EB
 * @property int $id
 * @property int $merchant_id
 * @property string $html
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Merchant $merchant
 * @property Installation[] $installations
 * @package App\Basket
 */
class Template extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'templates';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id',
        'html',
    ];
    
    /**
     *
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo('App\Basket\Merchant');
    }

    /**
     * @author EB
     * @return $this
     */
    public function installations()
    {
        return $this->belongsToMany('App\Basket\Installation')->withPivot('template_id');
    }
}
