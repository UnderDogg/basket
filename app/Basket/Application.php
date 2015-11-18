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
use App\ExportableModelInterface;

/**
 * Class Application
 *
 * @property int $id
 * @property int $user_id
 * @property int $installation_id
 * @property int $location_id
 * @property int $ext_id
 * @property string $ext_current_status
 * @property string $ext_order_reference
 * @property int $ext_order_amount
 * @property string $ext_order_description
 * @property string $ext_order_validity
 * @property string $ext_products_options
 * @property string $ext_products_groups
 * @property string $ext_finance_option_group
 * @property string $ext_products_default
 * @property string $ext_fulfilment_method
 * @property string $ext_fulfilment_location
 * @property string $ext_customer_title
 * @property string $ext_customer_first_name
 * @property string $ext_customer_last_name
 * @property string $ext_customer_email_address
 * @property string $ext_customer_phone_home
 * @property string $ext_customer_phone_mobile
 * @property string $ext_customer_postcode
 * @property string $ext_application_address_abode
 * @property string $ext_application_address_building_name
 * @property string $ext_application_address_building_number
 * @property string $ext_application_address_street
 * @property string $ext_application_address_locality
 * @property string $ext_application_address_town
 * @property string $ext_application_address_postcode
 * @property string $ext_applicant_title
 * @property string $ext_applicant_first_name
 * @property string $ext_applicant_last_name
 * @property string $ext_applicant_date_of_birth
 * @property string $ext_applicant_email_address
 * @property string $ext_applicant_phone_home
 * @property string $ext_applicant_phone_mobile
 * @property string $ext_applicant_postcode
 * @property int $ext_finance_order_amount
 * @property int $ext_finance_loan_amount
 * @property int $ext_finance_deposit
 * @property int $ext_finance_subsidy
 * @property int $ext_finance_commission
 * @property int $ext_finance_net_settlement
 * @property string $ext_metadata
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_sync_at
 * @property Installation $installation
 * @property Location|null $location
 * @property string $ext_finance_option
 * @property int $ext_finance_holiday
 * @property int $ext_finance_payments
 * @property int $ext_finance_term
 * @property int $ext_cancellation_requested
 * @property \Carbon\Carbon|null $ext_cancellation_effective_date
 * @property \Carbon\Carbon|null $ext_cancellation_requested_date
 * @property string $ext_cancellation_description
 * @property int $ext_cancellation_fee_amount
 *
 * @author MS
 * @package App\Basket
 */
class Application extends Model implements ExportableModelInterface
{
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
        'user_id',
        'installation_id',
        'location_id',
        'ext_id',
        'ext_current_status',
        'ext_order_reference',
        'ext_order_amount',
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
        'ext_finance_order_amount',
        'ext_finance_loan_amount',
        'ext_finance_deposit',
        'ext_finance_subsidy',
        'ext_finance_commission',
        'ext_finance_option_group',
        'ext_finance_net_settlement',
        'ext_cancellation_requested',
        'ext_cancellation_effective_date',
        'ext_cancellation_requested_date',
        'ext_cancellation_description',
        'ext_cancellation_fee_amount',
        'ext_metadata',
        'last_sync_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'ext_cancellation_effective_date',
        'ext_cancellation_requested_date',
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

    /**
     * Get an Export Safe version of the model to generate a CSV/JSON Export.
     *
     * @author SL
     */
    public function getExportableFields()
    {
        return [
            'Received' => $this->created_at,
            'ApplicationReference' => $this->ext_id,
            'RetailerReference' => $this->ext_order_reference,
            'LoanAmount' => $this->getFormattedCurrency($this->ext_finance_loan_amount),
            'Deposit' => $this->getFormattedCurrency($this->ext_finance_deposit),
            'Subsidy' => $this->getFormattedCurrency($this->ext_finance_subsidy),
            'Commission' => $this->getFormattedCurrency($this->ext_finance_commission),
            'NetSettlement' => $this->getFormattedCurrency($this->ext_finance_net_settlement),
            'CurrentStatus' => $this->ext_current_status,
            'OrderAmount' => $this->getFormattedCurrency($this->ext_order_amount),
            'FinanceGroup' => $this->ext_finance_option_group,
            'FinanceProduct' => $this->ext_finance_option,
            'TermLength' => $this->ext_finance_term,
            'InstallationName' => $this->installation->name,
            'FirstName' => $this->ext_customer_first_name,
            'LastName' => $this->ext_customer_last_name,
            'Email' => $this->ext_customer_email_address,
            'PhoneHome' => $this->ext_customer_phone_home,
            'PhoneMobile' => $this->ext_customer_phone_mobile,
            'AddrBuildingName' => $this->ext_application_address_building_name,
            'AddrBuildingNumber' => $this->ext_application_address_building_number,
            'AddrStreet' => $this->ext_application_address_street,
            'AddrTown' => $this->ext_application_address_town,
            'AddrPostcode' => $this->ext_application_address_postcode,
            'OrderDescription' => $this->ext_order_description
        ];
    }

    /**
     * Get a currency formatted version of the passed in amount.
     *
     * @author SL
     * @param int $fieldData
     * @return float
     */
    private function getFormattedCurrency($fieldData)
    {
        return $fieldData/100;
    }
}
