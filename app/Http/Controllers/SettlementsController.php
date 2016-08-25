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
use DateTime;
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
        $local = [];
        foreach ($settlementReports as $key => $report) {
            $settlementReports[$key] = (object) $report;
            $local[$report['id']] = Application::where('ext_id', '=', $report['id'])->first();
        }

        return View('settlements.index', [
            'settlement_reports' => $settlementReports,
            'default_dates' => $this->getDateRange(),
            'provider' => $this->fetchFilterValues($settlementReports, 'provider'),
            'local' => $local,
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
        try {
            $settlementReport = $this
                ->settlementGateway
                ->getSingleSettlementReport($this->fetchMerchantById($merchant)->token, $id);
        } catch (\Exception $e) {
            throw $this->redirectWithException('/', 'Failed fetching settlements', $e);
        }

        $this->applySettlementAmounts($settlementReport);

        return View('settlements.settlement_report', [
            'settlementReport' => $settlementReport,
            'installation' => Application::where('ext_id', '=', $settlementReport['id'])->first(),
            'api_data' => $this->flattenRawReport($settlementReport),
            'export_api_filename' => 'settlement-raw-' . $settlementReport['id'] . '-'
                .date_format(DateTime::createFromFormat('Y-m-d', $settlementReport['settlement_date']), 'Ymd'),
            'view_data' => $this->flattenViewReport($settlementReport),
            'export_view_filename' => 'settlement-report-' . $settlementReport['id'] . '-'
                . date_format(DateTime::createFromFormat('Y-m-d', $settlementReport['settlement_date']), 'Ymd'),

        ]);
    }

    /**
     * @author EA
     * @param array $report
     * @return array
     */
    private function flattenRawReport(array $report)
    {
        $rtn = [];

        foreach ($report['settlements'] as $settlement) {
            foreach ($settlement['transactions'] as $tx) {
                $rtn[] = [
                    'Order Date' => date('d/m/Y', strtotime($settlement['received_date'])),
                    'Customer' => $settlement['customer_name'],
                    'Post Code' => $settlement['application_postcode'],
                    'Retailer Reference' => $settlement['order_reference'],
                    'Order Amount' =>  number_format( $settlement['order_amount']/100, 2, '.', ''),
                    'Notification Date' => date('d/m/Y', strtotime($settlement['captured_date'])),
                    'Type' => $tx['type'],
                    'Description' => $settlement['description'],
                    'Deposit' =>  number_format( $settlement['deposit']/100, 2, '.', ''),
                    'Settlement Amount' => number_format($tx['amount']/100, 2, '.', ''),
                ];
            }
        }
        return $rtn;
    }

    /**
     * @author EA
     * @param array $report
     * @return array
     */
    private function flattenViewReport(array $report)
    {
        $rtn = [];

        foreach ($report['settlements'] as $settlement) {
            $rtn[] = [
                'Order Date' => date('d/m/Y', strtotime($settlement['received_date'])),
                'Notification Date' => date('d/m/Y', strtotime($settlement['captured_date'])),
                'Customer' => $settlement['customer_name'],
                'Post Code' => $settlement['application_postcode'],
                'Application ID' =>  $settlement['application'],
                'Retailer Reference' => $settlement['order_reference'],
                'Order Amount' => number_format($settlement['order_amount']/100, 2, '.', ''),
                'Type' => $settlement['type'],
                'Deposit' => number_format($settlement['deposit']/100, 2, '.', ''),
                'Loan Amount' => number_format($settlement['loan_amount']/100, 2, '.', ''),
                'Subsidy' => number_format($settlement['subsidy']/100, 2, '.', ''),
                'Adjustment' => number_format($settlement['adjustment']/100, 2, '.', ''),
                'Settlement Amount' => number_format($settlement['net']/100, 2, '.', ''),
            ];
        }

        return $rtn;
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

            $deposit = $loanAmount = $fulfilmentSubsidy = $cancellationSubsidy = $partial = $reversalOfPartial = $manualAdjustment = 0;
            $type = '';

            foreach ($settlement['transactions'] as $transaction) {
                switch (strtolower($transaction['type'])) {
                    case 'fulfilment':
                        $type = 'Fulfilment';
                        $deposit =  $settlement['deposit'];
                        $loanAmount = $transaction['amount'] - $settlement['deposit'];
                        break;
                    case 'refund':
                        $type = 'Cancellation';
                        $deposit = -$settlement['deposit'];
                        $loanAmount = $transaction['amount'] + $settlement['deposit'];
                        break;
                    case 'partial refund':
                        $type = 'Refund';
                        $partial = $partial + $transaction['amount'];
                        break;
                    case 'reversal of partial refund':
                        $reversalOfPartial = $reversalOfPartial + $transaction['amount'];
                        break;
                    case 'merchant fee charged':
                    case 'merchant commission':
                        $fulfilmentSubsidy = $fulfilmentSubsidy + $transaction['amount'];
                        break;
                    case 'merchant fee refunded':
                    case 'merchant commission refunded':
                        $cancellationSubsidy = $cancellationSubsidy + $transaction['amount'];
                        break;
                    case 'cancellation fee':
                        ($type == '' ? $type = 'Cancellation' : '');
                        $cancellationSubsidy = $cancellationSubsidy + $transaction['amount'];
                        break;
                    case 'manual adjustment':
                        $manualAdjustment = $manualAdjustment + $transaction['amount'];
                        break;
                }
            }

            $settlement['type'] = $type;
            $settlement['deposit'] = $deposit;
            $settlement['loan_amount'] = $loanAmount;

            $settlement['subsidy'] = $this->getSettlementSubsidy($type,$fulfilmentSubsidy,$cancellationSubsidy);
            $settlement['adjustment'] = $this->getSettlementAdjustment($type,$partial,$reversalOfPartial,$manualAdjustment);

            $settlement['net'] = $settlement['deposit'] +  $settlement['loan_amount'] + $settlement['subsidy'] + $settlement['adjustment'];
            $settlementReport['sum_net'] = $settlementReport['sum_net'] + $settlement['net'];
        }
    }

    /**
     * @author EA
     * @param string $type
     * @param int $partial
     * @param int $reversalOfPartial
     * @param int $manualAdjustment
     * @return int
     */
    private function getSettlementAdjustment($type, $partial, $reversalOfPartial, $manualAdjustment)
    {
        if ($type == 'Refund') {
            return  $partial + $manualAdjustment;
        }

        if ($type == 'Cancellation') {
            return $manualAdjustment + $reversalOfPartial;
        }

        return (int)$manualAdjustment;
    }

    /**
     * @author EA
     * @param string $type
     * @param int $fulfilmentSubsidy
     * @param int $cancellationSubsidy
     * @return int
     */
    private function getSettlementSubsidy($type, $fulfilmentSubsidy, $cancellationSubsidy)
    {
        if ($type == 'Fulfilment') {
            return  (int)$fulfilmentSubsidy;
        }

        if ($type == 'Cancellation') {
            return (int)$cancellationSubsidy;
        }

        return  0;
    }
}
