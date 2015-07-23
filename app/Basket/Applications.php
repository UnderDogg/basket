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
 * Class Applications
 *
 * @author MS
 * @package App\Basket
 */
class Applications extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applications';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester',
        'installation_id',
        'location_id',
        'ext_id',
        'ext_current_status',
        'ext_order_reference',
        'ext_order_amount',
        'ext_order_loan_amount',
        'ext_order_deposit',
        'ext_order_subsidy',
        'ext_order_net_settlement',
        'ext_order_description',
        'ext_order_validity',
        'ext_products_options',
        'ext_products_groups',
        'ext_products_default',
        'ext_fulfilment_method',
        'ext_fulfilment_location',
        'ext_customer_title',
        'ext_customer_first_name',
        'ext_customer_last_name',
        'ext_customer_email_address',
        'ext_customer_phone_home',
        'ext_customer_phone_mobile',
        'ext_customer_postcode',
        'ext_application_address_abode',
        'ext_application_address_building_name',
        'ext_application_address_building_number',
        'ext_application_address_street',
        'ext_application_address_locality',
        'ext_application_address_town',
        'ext_application_address_postcode',
        'ext_applicant_title',
        'ext_applicant_first_name',
        'ext_applicant_last_name',
        'ext_applicant_date_of_birth',
        'ext_applicant_email_address',
        'ext_applicant_phone_home',
        'ext_applicant_phone_mobile',
        'ext_applicant_postcode',
        'ext_metadata',
        'last_sync_at'
    ];

    /**
     * Get the user record for the application
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the installation record for the application
     */
    public function installation()
    {
        return $this->belongsTo('App\Basket\Installation');
    }

    /**
     * Get the location record for the application
     */
    public function location()
    {
        return $this->belongsTo('App\Basket\Location');
    }

}
