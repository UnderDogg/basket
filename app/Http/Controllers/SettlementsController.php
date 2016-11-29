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
use App\Basket\Merchant;
use App\Exceptions\RedirectException;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use PayBreak\Sdk\Gateways\SettlementCsvGateway;
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

    /** @var SettlementCsvGateway $settlementCsvGateway */
    protected $settlementCsvGateway;

    /**
     * @author MS, EA
     * @param SettlementGateway $settlementGateway
     * @param SettlementCsvGateway $settlementCsvGateway
     */
    public function __construct(SettlementGateway $settlementGateway, SettlementCsvGateway $settlementCsvGateway)
    {
        $this->settlementGateway = $settlementGateway;
        $this->settlementCsvGateway = $settlementCsvGateway;
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
     * @author MS, EA
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

            $aggregateSettlementReport = $this
                ->settlementGateway
                ->getSingleAggregateSettlementReport($this->fetchMerchantById($merchant)->token, $id);

        } catch (\Exception $e) {
            throw $this->redirectWithException('/', 'Failed fetching settlements', $e);
        }

        return View('settlements.settlement_report', [
            'settlement_report' => $settlementReport,
            'aggregate_settlement_report' => $aggregateSettlementReport,
            'aggregate_settlement_total' => array_sum(array_column($aggregateSettlementReport, 'settlement_amount')),
            'merchant' => Merchant::where('id', '=', $merchant)->first(),
            'api_data' => $this->flattenRawReport($settlementReport),
            'export_api_filename' => 'settlement-raw-' . $settlementReport['id'] . '-'
                . date_format(DateTime::createFromFormat('Y-m-d', $settlementReport['settlement_date']), 'Ymd'),
            'export_view_filename' => 'settlement-report-' . $settlementReport['id'] . '-'
                . date_format(DateTime::createFromFormat('Y-m-d', $settlementReport['settlement_date']), 'Ymd'),

        ]);
    }

    /**
     * @author EA
     * @param int $merchant
     * @param int $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function downloadSettlementReportCsv($merchant, $id)
    {
        try {

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'. Input::get('filename') . '.csv"',
            ];

            $csvResponse =  $this
                ->settlementCsvGateway
                ->getSingleAggregateSettlementReport($this->fetchMerchantById($merchant)->token, $id, true);

            return response()->make(
                stripcslashes($csvResponse['csv']),
                200,
                $headers
            );

        } catch (\Exception $e) {
            throw $this->redirectWithException('/', 'Failed to download settlements csv', $e);
        }
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
}
