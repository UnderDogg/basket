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
use App\Basket\Installation;
use App\Exceptions\RedirectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayBreak\Sdk\Gateways\ApplicationGateway;

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

    /** @var ApplicationGateway $applicationGateway */
    private $applicationGateway;

    /** @var \App\Basket\Email\EmailApplicationService */
    private $emailApplicationService;

    public function __construct(ApplicationGateway $applicationGateway)
    {
        $this->applicationSynchronisationService = \App::make(
            'App\Basket\Synchronisation\ApplicationSynchronisationService'
        );

        $this->applicationGateway = $applicationGateway;

        $this->emailApplicationService = \App::make(
            '\App\Basket\Email\EmailApplicationService'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @author WN, MS
     * @param int $installation
     * @return \Illuminate\View\View
     */
    public function index($installation)
    {
        $filterDates = $this->getDateRange();

        $applications = $this->processDateFilters(
            Application::query(),
            'created_at',
            $filterDates['date_from'],
            $filterDates['date_to']
        );

        $this->limitToInstallationOnMerchant($applications->where('installation_id', $installation));

        return $this->standardIndexAction(
            $applications->orderBy('created_at', 'DESC'),
            'applications.index',
            'applications',
            [
                'default_dates' => $filterDates,
                'ext_current_status' => $this->fetchFilterValues($applications, 'ext_current_status'),
                'ext_finance_option_group' => $this->fetchFilterValues($applications, 'ext_finance_option_group'),
                'export_custom_filename' => 'applications-export-'.date('Ymd-Hi'),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $installation
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($installation, $id)
    {
        $application = $this->fetchApplicationById($id, $installation);

        return view(
            'applications.show',
            [
                'applications' => $application,
                'fulfilmentAvailable' => $this->isFulfilable($application),
                'cancellationAvailable' => $this->isCancellable($application),
                'partialRefundAvailable' => $this->canPartiallyRefund($application),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $installation
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($installation, $id)
    {
        return view(
            'applications.edit',
            ['applications' => $this->fetchApplicationById($id)]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param $installation
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function update($installation, $id, Request $request)
    {
        return $this->updateModel(
            (new Application()),
            $id,
            'application',
            '/installations/' . $installation . '/applications', $request
        );
    }

    /**
     * @author WN
     * @param $installation
     * @param $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function confirmFulfilment($installation, $id)
    {
        return $this->renderConfirmationScreen('fulfilment', $id, $installation);
    }

    /**
     * @author WN
     * @param $installation
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function fulfil($installation, $id)
    {
        try {
            $this->applicationSynchronisationService->fulfil($id);
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                '/installations/' . $installation . '/applications/' . $id,
                'Error while trying to fulfil Application[' . $id . ']',
                $e
            );
        }
        return $this->redirectWithSuccessMessage(
            '/installations/' . $installation . '/applications/' . $id,
            'Application was fulfilled successfully'
        );
    }

    /**
     * @author WN
     * @param $installation
     * @param int $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function confirmCancellation($installation, $id)
    {
        return $this->renderConfirmationScreen('cancellation', $id, $installation);
    }

    /**
     * @author WN
     * @param $installation
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function requestCancellation($installation, $id, Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
        ]);

        try {
            $this->applicationSynchronisationService->requestCancellation($id, $request->get('description'));
        } catch(\Exception $e) {
            throw $this->redirectWithException('/installations/' . $installation . '/applications','Failed to request cancellation', $e);
        }
        return $this->redirectWithSuccessMessage(
            '/installations/' . $installation . '/applications/' . $id,
            'Cancellation requested successfully'
        );
    }

    /**Display pending cancellation list.
     *
     * @author SD, EB
     * @param $installationId
     * @return \Illuminate\View\View
     */
    public function pendingCancellations($installationId)
    {
        $installation = Installation::query()->findOrFail($installationId);

        $pendingCancellations = Application::query()
            ->where('installation_id', '=', $installationId)
            ->where('ext_current_status', '=', 'pending_cancellation')
            ->get();

        return View('applications.pending-cancellation',
            [
                'applications' => $pendingCancellations,
                'installation' => $installation,
            ]);
    }

    /**
     * @author LH
     * @param $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function confirmPartialRefund($installation, $id)
    {
        $application = $this->fetchApplicationById($id, $installation);
        if (!$this->canPartiallyRefund($application)) {

            throw RedirectException::make('/installations/' . $installation . '/applications/' . $id)
                ->setError('You may not partially refund this application.');
        }
        return view('applications.partial-refund', ['application' => $application]);
    }

    /**
     * @author LH
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function requestPartialRefund(Request $request, $installation, $id)
    {
        $application = $this->fetchApplicationById($id, $installation);
        if ($application->ext_order_amount / 100 == $request->refund_amount) {
            throw RedirectException::make('/installations/' . $installation . '/applications/' . $id)
                ->setError('Cannot request partial refund for the full amount, you must request cancellation.');
        }
        $this->validate($request, [
            'refund_amount' => 'required|numeric|max:' . $application->ext_order_amount/100,
            'effective_date' => 'required|date_format:Y/m/d',
            'description' => 'required',
        ]);

        $effectiveDate = \DateTime::createFromFormat('Y/m/d', $request->get('effective_date'))->format('Y-m-d');

        try {
            $this->applicationSynchronisationService->requestPartialRefund(
                $id,
                ($request->get('refund_amount') * 100),
                $effectiveDate,
                $request->get('description')
            );
        } catch (\Exception $e) {
            $this->logError('Error while trying to request a partial refund for application [' . $id . ']: '
                . $e->getMessage());
            throw RedirectException::make('/installations/' . $installation . '/applications/' . $id)
                ->setError(($e->getMessage()) ? $e->getMessage() : 'Requesting a partial refund failed');
        }
        return $this->redirectWithSuccessMessage(
            '/installations/' . $installation . '/applications/' . $id,
            'Partial refund has been successfully requested'
        );
    }

    /**
     * @author WN
     * @param int $id
     * @return Application
     * @throws RedirectException
     */
    private function fetchApplicationById($id, $installation)
    {
        return $this->fetchModelByIdWithInstallationLimit(
            (new Application()), $id, 'application', 'installations/' . $installation . '/applications'
        );
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

    /**
     * @author LH
     * @param Application $application
     * @return bool
     */
    private function canPartiallyRefund(Application $application)
    {
        return in_array($application->ext_current_status, ['converted', 'fulfilled', 'complete']);
    }

    /**
     * @author WN
     * @param string $action
     * @param $id
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    private function renderConfirmationScreen($action, $id, $installation)
    {
        $application = $this->fetchApplicationById($id, $installation);

        if (((!$this->isCancellable($application)) && $action == 'cancellation') ||
            ((!$this->isFulfilable($application)) && $action == 'fulfilment')
        ) {
            Log::error('Application is not allowed to request ' . $action);
            throw RedirectException::make('/installations/' . $installation . '/applications/' . $id)
                ->setError('Application is not allowed to request ' . $action);
        }
        return view('applications.' . $action, ['application' => $application]);
    }

    /**
     * @autgir EB
     * @param $installation
     * @param $id
     * @param Request $request
     */
    public function emailApplication($installation, $id, Request $request)
    {
        $this->validate(
            $request,
            [
                'title' => 'required|in:Mr,Mrs,Miss,Ms',
                'first_name' => 'required|max:30',
                'last_name' => 'required|max:30',
                'email' => 'required|email|max:30',
                'subject' => 'required|max:100',
                'description' => 'required|max:255',
            ]
        );

        $application = $this->fetchApplicationById($id, $installation);

        // Rest of the method requires Template Engine

    }
}
