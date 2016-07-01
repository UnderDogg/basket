<?php

namespace App\Http\Controllers;

use PayBreak\Sdk\Gateways\CreditInfoGateway;

/**
 * Class AjaxController
 *
 * @package App\Http\Controllers
 * @author SL
 */
class AjaxController extends Controller
{
    /**
     * @var CreditInfoGateway
     */
    private $creditInfoGateway;

    /**
     * AjaxController constructor.
     *
     * @param CreditInfoGateway $creditInfoGateway
     */
    public function __construct(CreditInfoGateway $creditInfoGateway)
    {
        $this->creditInfoGateway = $creditInfoGateway;
    }

    public function getCreditInformationForProduct()
    {
        throw new Exception();
        return '{"amount_service":0,"apr":29.8,"customer_settlement_fee":2900,"deposit_amount":5000,"deposit_range":{"maximum_amount":10000,"minimum_amount":5000},"holiday":6,"initial_payment_upfront":true,"loan_amount":45000,"loan_cost":20205,"loan_repayment":65205,"offered_rate":26.4,"order_amount":50000,"payment_final":2714,"payment_regular":2717,"payment_start_iso":"2015-09-17","payment_start_nice":"Thursday 17th September 2015","payments":24,"promotional":{"customer_settlement_fee":2900,"date_end_iso":"2015-09-17","date_end_nice":"Thursday 17th September 2015","deposit_amount":5000,"loan_amount":45000,"order_amount":50000,"term":6,"total_cost":52900},"total_cost":70205,"total_repayment":70205}';
    }
}