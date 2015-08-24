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
        $partialRefunds = Collection::make(
            $this->partialRefundGateway->listPartialRefunds($this->fetchMerchantById($id)->token)
        );

        foreach ($partialRefunds as $key => $report) {
            $partialRefunds[$key] = (object) $report->toArray();
        }

        $statuses = $partialRefunds->pluck('status')->unique()->flip();
        foreach ($statuses as $key => $value) {
            $statuses[$key] = ucfirst($key);
        }

        $filter = $this->getFilters();

        if (!$filter->isEmpty()) {
            if (isset($filter['status'])) {
                $partialRefunds = $partialRefunds->filter(function ($partialRefunds) use ($filter) {
                    if ($partialRefunds->status == $filter['status']) {
                        return true;
                    }
                });
            }
        }

        return View('partial-refunds.index', [
            'partialRefunds' => $partialRefunds,
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
        $partialRefund = $this->partialRefundGateway
            ->getPartialRefund($this->fetchMerchantById($merchant)->token, $partialRefundId);
        return View('partial-refunds.show', [
            'partialRefund' => (object) $partialRefund->toArray(),
        ]);
    }
}
