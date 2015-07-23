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
 * @package App\Basket
 */
class Location extends Model
{
    protected $table = 'locations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'installation_id',
        'active',
        'name',
        'email',
        'address',
    ];

    /**
     * Get the installation record for the application
     */
    public function installation()
    {
        return $this->belongsTo('App\Basket\Installation');
    }
}
