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
 * Merchant Model
 *
 * @author WN
 * @property int    $id
 * @property string $name
 * @property string $token
 * @property bool   $linked
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
        'active',
    ];
}
