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

use Illuminate\Support\Collection;
use PayBreak\Sdk\Gateways\PartialRefundGateway;

/**
 * Partial Refunds Controller
 *
 * @package App\Http\Controllers
 */
class PartialRefundsController extends Controller
{
    protected $partialRefundGateway;

    /**
     * @param PartialRefundGateway $partialRefundGateway
     */
    public function __construct(PartialRefundGateway $partialRefundGateway)
    {
        $this->partialRefundGateway = $partialRefundGateway;
    }

    /**
     * Index Partial Refunds
     *
     * @author LH
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $messages = $this->getMessages();

        $settlementReports = Collection::make(
            $this->partialRefundGateway->listPartialRefunds($this->fetchMerchantById($id)->token)
        );

        foreach ($settlementReports as $key => $report) {
            $settlementReports[$key] = (object) $report->toArray();
        }

        $statuses = $settlementReports->pluck('status')->unique()->flip();
        foreach ($statuses as $key => $value) {
            $statuses[$key] = ucfirst($key);
        }

        $filter = $this->getFilters();

        if (!$filter->isEmpty()) {
            if (isset($filter['status'])) {
                $settlementReports = $settlementReports->filter(function ($settlement_reports) use ($filter) {
                    if ($settlement_reports->status == $filter['status']) {
                        return true;
                    }
                });
            }
        }

        return View('partial-refunds.index', [
            'settlement_reports' => $settlementReports,
            'messages' => $messages,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show a Partial Refund
     *
     * @author LH
     * @param int $merchant
     * @param int $partialRefundId
     * @return \Illuminate\View\View
     */
    public function show($merchant, $partialRefundId)
    {
        $messages = $this->getMessages();
        $partialRefund = $this->partialRefundGateway->getPartialRefund($this->fetchMerchantById($merchant)->token, $partialRefundId);
        return View('partial-refunds.show', [
            'partialRefund' => (object) $partialRefund->toArray(),
            'messages' => $messages,
        ]);
    }
}
