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
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use PayBreak\Sdk\Gateways\PartialRefundGateway;

/**
 * Partial Refunds Controller
 *
 * @package App\Http\Controllers
 */
class PartialRefundsController extends Controller
{
    const REFUNDS_PER_PAGE = 4;

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
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index($id, Request $request)
    {
        $partialRefunds = Collection::make(
            $this->partialRefundGateway->listPartialRefunds($this->fetchMerchantById($id)->token)
        );

        $local = [];

        foreach ($partialRefunds as $key => $report) {
            $partialRefunds[$key] = (object) $report->toArray();
            $local[$partialRefunds[$key]->application] =
                $this->fetchApplicationDetails($partialRefunds[$key]->application);
        }

        $statuses = $this->processFilter($partialRefunds);

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
            'partial_refunds' => $this->convertCollectionToPaginator($partialRefunds, $request),
            'statuses' => $statuses,
            'status' => $this->fetchFilterValues($partialRefunds, 'status'),
            'local' => $local,
        ]);
    }

    /**
     * @author SL
     * @param Collection $collection
     * @param Request $request
     * @return LengthAwarePaginator
     */
    private function convertCollectionToPaginator(Collection $collection, Request $request)
    {
        $paginator = new LengthAwarePaginator($collection->forPage(Input::get('page', 1), self::REFUNDS_PER_PAGE), $collection->count(), self::REFUNDS_PER_PAGE);
        $paginator->withPath($request->path());

        return $paginator;
    }

    /**
     * @author WN
     * @param int $id
     * @return array
     */
    private function fetchLocalApplication($id)
    {
        if ($temp = Application::where('ext_id', '=', $id)->first()) {
            return ['installation' => $temp->installation_id, 'id' => $temp->id];
        }

        return [];
    }

    /**
     * @author WN
     * @param Collection $partialRefunds
     * @return Collection
     */
    private function processFilter(Collection $partialRefunds)
    {
        $statuses = $partialRefunds->pluck('status')->unique()->flip();
        foreach ($statuses as $key => $value) {
            $statuses[$key] = ucfirst($key);
        }
        return $statuses;
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
            'installation' => Application::where('ext_id', '=', $partialRefund->toArray()['application'])->first(),
        ]);
    }
}
