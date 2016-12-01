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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
    const RAW_SETTLEMENT_REPORT = 'raw-settlement-report';
    const AGGREGATE_SETTLEMENT_REPORT = 'aggregate-settlement-report';
    
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function settlementReport($merchant, $id, Request $request)
    {
        try {

            $aggregateSettlementReport = $this
                ->settlementGateway
                ->getSingleAggregateSettlementReport($this->fetchMerchantById($merchant)->token, $id);

        } catch (\Exception $e) {
            throw $this->redirectWithException('/', 'Failed fetching settlements', $e);
        }

        $settlementDate = $request->get('date');

        return View('settlements.settlement_report', [
            'settlement_date' => $settlementDate,
            'settlement_amount' => $request->get('amount', ''),
            'settlement_provider' => $request->get('provider', ''),
            'aggregate_settlement_report' => $aggregateSettlementReport,
            'aggregate_settlement_total' => array_sum(array_column($aggregateSettlementReport, 'settlement_amount')),
            'merchant' => Merchant::where('id', '=', $merchant)->first(),
            'export_api_filename' => 'settlement-raw-' . $id . '-'
                . date_format(DateTime::createFromFormat('Y-m-d', $settlementDate), 'Ymd'),
            'export_view_filename' => 'settlement-report-' . $id . '-'
                . date_format(DateTime::createFromFormat('Y-m-d', $settlementDate), 'Ymd'),

        ]);
    }

    /**
     * @author EA
     * @param int $merchant
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function downloadSettlementReportCsv($merchant, $id, Request $request)
    {
        try {
            switch ($request->get('type', null)) {
                case self::AGGREGATE_SETTLEMENT_REPORT:
                    $csvResponse =  $this
                        ->settlementCsvGateway
                        ->getSingleAggregateSettlementReport($this->fetchMerchantById($merchant)->token, $id, true);
                    break;
                case self::RAW_SETTLEMENT_REPORT:
                   $csvResponse =  $this
                       ->settlementCsvGateway
                       ->getSingleSettlementReport($this->fetchMerchantById($merchant)->token, $id, true);
                     break;
                default:
                    throw new Exception('Settlement report type not found');
            }

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'. $request->get('filename') . '.csv"',
            ];

            return response()->make(
                stripcslashes($csvResponse['csv']),
                200,
                $headers
            );

        } catch (\Exception $e) {
            throw $this->redirectWithException('/', 'Failed to download settlements csv', $e);
        }
    }
}
