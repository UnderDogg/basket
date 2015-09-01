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

use App\Basket\Installation;
use App\Exceptions\RedirectException;
use App\Http\Requests;
use App\Basket\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PayBreak\Sdk\Gateways\ApplicationGateway;
use Illuminate\Support\Facades\Log;

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

    public function __construct(ApplicationGateway $applicationGateway)
    {
        $this->applicationSynchronisationService = \App::make(
            'App\Basket\Synchronisation\ApplicationSynchronisationService'
        );

        $this->applicationGateway = $applicationGateway;
    }

    /**
     * Display a listing of the resource.
     *
     * @author WN, MS
     * @param int $installation
     * @return Response
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

        $applications->where('installation_id', $installation);

        $this->limitToInstallationOnMerchant($applications);

        //Creating the filter variables in the controller, so it doesn't have to be created on front end (EB)
        $select = [];
        if($applications) {
            foreach($applications->get() as $item) {
                $select[strtolower($item->ext_current_status)] = ucwords($item->ext_current_status);
            }
        }

        return $this->standardIndexAction(
            $applications->orderBy('created_at', 'DESC'),
            'applications.index',
            'applications',
            [
                'default_dates' => $filterDates,
                'select' => $select,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $installation
     * @param  int $id
     * @return Response
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
     * @return Response
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
     * @return Response
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
     * @throws ApplicationsController
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
     * @param int $installation
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ApplicationsController
     */
    public function requestCancellation($installation, $id, Request $request)
    {
        try {
            $this->applicationSynchronisationService->requestCancellation($id, $request->get('description'));
        } catch(\Exception $e) {
            throw $this->redirectWithException('/installations/' . $installation . '/applications','Failed to request cancellation', $e);
        }
        return $this->redirectWithSuccessMessage(
            '/installations/' . $installation . '/applications',
            'Cancellation requested successfully'
        );
    }

    /**
     * Display pending cancellation list.
     *
     * @author SD
     * @return \Illuminate\View\View
     * @throws \App\Exceptions\RedirectException
     */
    public function pendingCancellations($installationId)
    {
        $installation = $this->fetchModelByIdWithMerchantLimit((new Installation()), $installationId, 'installation', '/');

        $pendingCancellations = Collection::make(
            $this
                ->applicationGateway
                ->getPendingCancellations($installation->ext_id, $installation->merchant->token)
        );

        // Shouldn't need to do this but leaving for refactoring as this
        // is done across all code base
        foreach ($pendingCancellations as $key => $pendingCancellation) {
            $pendingCancellations[$key] = (object) $pendingCancellation;
        }

        return View('applications.pending-cancellation', [
            'applications' => $pendingCancellations,
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
        $this->validate($request, [
            'refund_amount' => 'required|numeric',
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
                ->setError('Requesting a partial refund failed');
        }
        return $this->redirectWithSuccessMessage(
            '/applications',
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
     * @param $action
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
}
