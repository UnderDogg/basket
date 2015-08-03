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

use App\Exceptions\RedirectException;
use App\Http\Requests;
use App\Basket\Application;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class ApplicationsController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class ApplicationsController extends Controller
{
    /** @var \App\Basket\Synchronisation\ApplicationSynchronisationService */
    private $applicationSynchronisationService;

    public function __construct()
    {
        $this->applicationSynchronisationService = \App::make(
            'App\Basket\Synchronisation\ApplicationSynchronisationService'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @author WN, MS
     * @return Response
     */
    public function index()
    {
        $filterDates = $this->getDateRange();
        $application = Application::query()->where('created_at', '>', $filterDates[1])->where('created_at', '<', $filterDates[0]);
        $this->limitToInstallationOnMerchant($application);
        return $this->filterDateIndexAction($application, 'applications.index', 'applications', $filterDates);
    }

    private function getDateRange() {
        $date_to = Carbon::now();
        $date_from = new Carbon('last month');

        $default_dates[0] = $date_to;
        $default_dates[1] = $date_from;

        if(!empty($filter = $this->getTableFilter())) {

            foreach ($filter as $field => $query) {
                $newDate = Carbon::createFromFormat('Y/m/d', $query);
                $newDate->hour = 23; $newDate->minute = 59; $newDate->second = 59;
                $default_dates[0] = ($field == 'date_to') ? $newDate : $default_dates[0];
                $default_dates[1] = ($field == 'date_from') ? $newDate : $default_dates[1];
            }
        }
        return $default_dates;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $application = $this->fetchApplicationById($id);

        return view(
            'applications.show',
            [
                'applications' => $application,
                'messages' => $this->getMessages(),
                'fulfilmentAvailable' => $this->isFulfilable($application),
                'cancellationAvailable' => $this->isCancellable($application),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view(
            'applications.edit',
            ['applications' => $this->fetchApplicationById($id), 'messages' => $this->getMessages()]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param int     $id
     * @param Request $request
     * @return Response
     * @throws RedirectException
     */
    public function update($id, Request $request)
    {
        return $this->updateModel((new Application()), $id, 'application', '/applications', $request);
    }

    /**
     * @author WN
     * @param $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function confirmFulfilment($id)
    {
        $application = $this->fetchApplicationById($id);
        if (!$this->isFulfilable($application)) {

            throw RedirectException::make('/applications/' . $id)
                ->setError('Application is not allowed to be fulfilled.');
        }
        return view('applications.fulfilment', ['application' => $application]);
    }

    /**
     * @author WN
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function fulfil($id)
    {
        try {
            $this->applicationSynchronisationService->fulfil($id);
        } catch (\Exception $e) {
            $this->logError('Error while trying to fulfil Application[' . $id . ']: ' . $e->getMessage());
            throw RedirectException::make('/applications/' . $id)->setError('Fulfilment failed');
        }
        return redirect()->back()->with('success', 'Application was fulfilled successfully');
    }

    /**
     * @author WN
     * @param int $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function confirmCancellation($id)
    {
        $application = $this->fetchApplicationById($id);
        if (!$this->isCancellable($application)) {

            throw RedirectException::make('/applications/' . $id)
                ->setError('Application is not allowed to request cancellation.');
        }
        return view('applications.cancellation', ['application' => $application]);
    }

    /**
     * @author WN
     * @param int  $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function requestCancellation($id, Request $request)
    {
        try {
            $this->applicationSynchronisationService->requestCancellation($id, $request->get('description'));
        } catch (\Exception $e) {
            $this->logError('Error while trying to request cancellation Application[' . $id . ']: ' . $e->getMessage());
            throw RedirectException::make('/applications/' . $id)->setError('Request cancellation failed');
        }
        return redirect()->back()->with('success', 'Cancellation requested successfully');
    }

    /**
     * Reformat For Currency
     *
     * @author MS
     * @param string $field
     * @param int|float $integer
     * @return int
     */
    private function reformatForCurrency($field, $integer)
    {
        if (
            !($field === 'ext_order_amount') &&
            !($field === 'ext_finance_order_amount') &&
            !($field === 'ext_finance_loan_amount') &&
            !($field === 'ext_finance_deposit') &&
            !($field === 'ext_finance_subsidy') &&
            !($field === 'ext_finance_net_settlement')
        ) {

            return $integer;
        }
        return round($integer * 100);
    }

    /**
     * @author WN
     * @param int $id
     * @return Application
     * @throws RedirectException
     */
    private function fetchApplicationById($id)
    {
        return $this->fetchModelByIdWithInstallationLimit((new Application()), $id, 'application', '/applications');
    }

    /**
     * @author WN
     * @param Application $application
     * @return bool
     */
    private function isFulfilable(Application $application)
    {
        return $application->ext_current_status === 'converted';
    }

    /**
     * @author WN
     * @param Application $application
     * @return bool
     */
    private function isCancellable(Application $application)
    {
        return in_array($application->ext_current_status, ['converted', 'fulfilled', 'complete']);
    }
}
