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
 * ProductLimit Model
 *
 * @author EB
 * @property int $id
 * @property int $installation_id
 * @property string $product
 * @property int $min_deposit_percentage
 * @property int $max_deposit_percentage
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property Installation $installation
 * @package App\Basket
 */
class ProductLimit extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_limits';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'installation_id',
        'product',
        'min_deposit_percentage',
        'max_deposit_percentage',
    ];

    /**
     *
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function installation()
    {
        return $this->belongsTo('App\Basket\Installation');
    }
}
