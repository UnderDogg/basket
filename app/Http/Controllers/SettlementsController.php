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
use PayBreak\Sdk\Gateways\SettlementGateway;
use Carbon\Carbon;

/**
 * Class SettlementsController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class SettlementsController extends Controller
{
    /** @var SettlementGateway $settlementGateway */
    protected $settlementGateway;

    /**
     * @author MS
     * @param SettlementGateway $settlementGateway
     */
    public function __construct(SettlementGateway $settlementGateway)
    {
        $this->settlementGateway = $settlementGateway;
    }

    /**
     * Index
     *
     * @author MS
     * @return \Illuminate\View\View
     * @throws \App\Exceptions\RedirectException
     */
    public function index()
    {
        $messages = $this->getMessages();

        $settlement_reports = $this
            ->settlementGateway
            ->getSettlementReports($this->getMerchantToken(), $this->getDateRange());

        $this->applyStandardFilters($settlement_reports);

        foreach ($settlement_reports as $key => $report) {
            $settlement_reports[$key] = (object) $report;
        }

        return View('settlements.index', [
            'settlement_reports' => (object) $settlement_reports,
            'default_dates' => $this->getDateRange(),
            'messages' => $messages
        ]);
    }

    /**
     * Settlement Report
     *
     * @author MS
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function settlementReport($id)
    {
        $messages = $this->getMessages();

        $settlementReport = $this
            ->settlementGateway
            ->getSingleSettlementReport($this->getMerchantToken(), $id);

        $this->applySettlementAmounts($settlementReport);

        return View('settlements.settlement_report', [
            'settlementReport' => $settlementReport,
            'messages' => $messages
        ]);
    }

    /**
     * Apply Standard Filters
     *
     * @author MS
     * @param array $settlements
     */
    private function applyStandardFilters(&$settlements)
    {
        if (!empty($filter = $this->getTableFilter())) {
            foreach ($filter as $field => $query) {
                if ($field !== 'date_from' && $field !== 'date_to') {
                    $this->filterArrayByValue($settlements, $field, $query);
                }
            }
        }
    }

    /**
     * Apply SettlementAmounts
     *
     * @author MS
     * @param $settlementReport
     */
    private function applySettlementAmounts(&$settlementReport)
    {
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
    }

    /**
     * Get Date Range
     *
     * @author MS
     * @return array
     */
    private function getDateRange()
    {
        $date_to = Carbon::now();
        $date_from = new Carbon('last month');

        $default_dates = [$date_from, $date_to];

        if (!empty($filter = $this->getTableFilter())) {

            foreach ($filter as $field => $query) {

                $default_dates[0] = ($field == 'date_from') ? $query : $default_dates[0];
                $default_dates[1] = ($field == 'date_to') ? $query : $default_dates[1];
            }
        }
        return $default_dates;
    }

    /**
     * Filter Array By Value
     *
     * @author MS
     * @param array $array
     * @param $index
     * @param $value
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
