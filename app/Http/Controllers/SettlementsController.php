<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Http\Controllers;

use App\Basket\Application;
use App\Http\Requests;
use App\Basket\Gateways\SettlementGateway;
use Carbon\Carbon;

/**
 * Class SettlementsController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class SettlementsController extends Controller
{
    protected $settlementGateway;

    public function __construct(SettlementGateway $settlementGateway)
    {
        $this->settlementGateway = $settlementGateway;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $messages = $this->getMessages();

        $date_to = Carbon::now();
        $date_from = new Carbon('last month');

        $default_dates = [$date_from, $date_to];

        if (!empty($filter = $this->getTableFilter())) {

            foreach ($filter as $field => $query) {

                $date_from = ($field == 'date_from') ? $query : $date_from;
                $date_to = ($field == 'date_to') ? $query : $date_to;
            }
        }

        $settlement_reports = $this->settlementGateway->getSettlementReports(
            '76d45dd6e7a543cba86116ead84911f4',
            [$date_from, $date_to]
        );

        if (!empty($filter)) {
            foreach ($filter as $field => $query) {
                if ($field !== 'date_from' && $field !== 'date_to') {
                    $this->filterArrayByValue($settlement_reports, $field, $query);
                }
            }
        }

        foreach ($settlement_reports as $key => $report) {
            $settlement_reports[$key] = (object) $report;
        }

        return View('settlements.index', ['settlement_reports' => (object) $settlement_reports, 'default_dates' => $default_dates, 'messages' => $messages]);
    }

    /**
     * Settlement Report
     *
     * @param $id
     */
    public function settlementReport($id)
    {
        $messages = $this->getMessages();

        $settlementReport = $this->settlementGateway->getSingleSettlementReport(
            '76d45dd6e7a543cba86116ead84911f4',
            $id
        );

        $settlementReport['sum_order_amount'] =
        $settlementReport['sum_subsidy'] =
        $settlementReport['sum_adjustment'] =
        $settlementReport['sum_net'] = 0;

        foreach ($settlementReport['settlements'] as &$settlement) {
            $settlement['application_data'] = Application::where('ext_id', '=', $settlement['application'])->first();

            $fulfilment = $feeCharged = $refund = $feeRefunded = $cancellationFee = $manualAdjustment = $partial = 0;

            foreach ($settlement['transactions'] as $transaction) {
                switch (strtolower($transaction['type'])) {
                    case 'fulfilment':
                        $fulfilment = $fulfilment + $transaction['amount'];
                        break;
                    case 'refund':
                        $refund = $refund + $transaction['amount'];
                        break;
                    case 'merchant fee charged':
                        $feeCharged = $feeCharged + $transaction['amount'];
                        break;
                    case 'merchant fee refunded':
                        $feeRefunded = $feeRefunded + $transaction['amount'];
                        break;
                    case 'cancellation fee':
                        $cancellationFee = $cancellationFee + $transaction['amount'];
                        break;
                    case 'manual adjustment':
                        $manualAdjustment = $manualAdjustment + $transaction['amount'];
                        break;
                    case 'partial refund':
                        $partial = $partial + $transaction['amount'];
                        break;
                }
            }

            $settlement['order_amount'] = $fulfilment + $refund;
            $settlement['subsidy'] = $feeCharged + $feeRefunded + $cancellationFee;
            $settlement['adjustment'] = $manualAdjustment + $partial;
            $settlement['net'] = $settlement['order_amount'] + $settlement['subsidy'] + $settlement['adjustment'];

            $settlementReport['sum_order_amount'] = $settlementReport['sum_order_amount'] + $settlement['order_amount'];
            $settlementReport['sum_subsidy'] = $settlementReport['sum_subsidy'] + $settlement['subsidy'];
            $settlementReport['sum_adjustment'] = $settlementReport['sum_adjustment'] + $settlement['adjustment'];
            $settlementReport['sum_net'] = $settlementReport['sum_net'] + $settlement['net'];
        }

        return View('settlements.settlement_report', ['settlementReport' => $settlementReport, 'messages' => $messages]);
    }



    /**
     * Filter Array By Value
     *
     * @todo Move to helper
     *
     */
    private function filterArrayByValue(&$array, $index, $value)
    {
        if(is_array($array) && count($array)>0)
        {
            foreach(array_keys($array) as $key){
                $temp[$key] = $array[$key][$index];

                if ($temp[$key] !== $value){

                    unset($array[$key]);
                }
            }
        }
    }
}
