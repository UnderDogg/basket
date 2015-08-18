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
use App\Exceptions\RedirectException;
use Illuminate\Support\Collection;
use PayBreak\Sdk\Gateways\SettlementGateway;

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
     * @param int $id
     * @return \Illuminate\View\View
     * @throws \App\Exceptions\RedirectException
     */
    public function index($id)
    {
        $messages = $this->getMessages();
        $dateRange = $this->getDateRange();

        try {
            $settlementReports = Collection::make(
                $this
                    ->settlementGateway
                    ->getSettlementReports(
                        $this->fetchMerchantById($id)->token, $dateRange['date_from'], $dateRange['date_to']
                    )
            );
        } catch (\Exception $e) {
            $this->logError('SettlementsController: failed fetching settlements' . $e->getMessage());
            throw RedirectException::make('/')->setError('Problem fetching Settlements.');
        }

        $filter = $this->getFilters();

        if(!$filter->isEmpty()) {
            $settlementReports = $settlementReports->filter(function($settlement_reports) use ($filter) {
                if($settlement_reports['provider'] == $filter['provider']) {
                    return true;
                }
            });
        }

        foreach ($settlementReports as $key => $report) {
            $settlementReports[$key] = (object) $report;
        }

        return View('settlements.index', [
            'settlement_reports' => $settlementReports,
            'default_dates' => $this->getDateRange(),
            'messages' => $messages
        ]);
    }

    /**
     * Settlement Report
     *
     * @author MS
     * @param int $merchant
     * @param int $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function settlementReport($merchant, $id)
    {
        $messages = $this->getMessages();

        try {
            $settlementReport = $this
                ->settlementGateway
                ->getSingleSettlementReport($this->getMerchantToken(), $id);
        } catch (\Exception $e) {
            $this->logError('SettlementsController: failed fetching settlements' . $e->getMessage());
            throw RedirectException::make('/')->setError('Problem fetching Settlements.');
        }

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
        if (!empty($filter = $this->getFilters())) {
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
