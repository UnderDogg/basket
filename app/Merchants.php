<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Merchants
 *
 * @author MS
 * @package App
 */
class Merchants extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
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

}
