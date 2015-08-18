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
     * @return \Illuminate\View\View
     * @throws \App\Exceptions\Exception
     */
    public function index()
    {
        $settlementReports = Collection::make(
            $this->partialRefundGateway->listPartialRefunds($this->getMerchantToken())
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
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show a Partial Refund
     *
     * @author LH
     * @param $partialRefundId
     * @return \Illuminate\View\View
     */
    public function show($partialRefundId)
    {
        $partialRefund = $this->partialRefundGateway->getPartialRefund($this->getMerchantToken(), $partialRefundId);
        return View('partial-refunds.show', [
            'partialRefund' => (object) $partialRefund->toArray(),
        ]);
    }
}
