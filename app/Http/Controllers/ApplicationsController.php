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
use App\Basket\ApplicationEvent;
use App\Basket\Email\EmailApplicationService;
use App\Basket\Email\EmailConfigurationTemplateHelper;
use App\Basket\Email\EmailTemplateEngine;
use App\Basket\Installation;
use App\Basket\Location;
use App\Basket\Synchronisation\ApplicationSynchronisationService;
use App\Exceptions\RedirectException;
use App\Http\Requests\ApplicationCancellationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PayBreak\Foundation\Exception;
use PayBreak\Sdk\Gateways\ApplicationGateway;

/**
 * Class ApplicationsController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class ApplicationsController extends Controller
{
    const MERCHANT_PAYMENT_LIMIT = 100;

    /** @var \App\Basket\Synchronisation\ApplicationSynchronisationService */
    private $applicationSynchronisationService;

    /** @var ApplicationGateway $applicationGateway */
    private $applicationGateway;

    /** @var \App\Basket\Email\EmailApplicationService */
    private $emailApplicationService;

    /**
     * ApplicationsController constructor.
     * @author ??, SL
     * @param ApplicationGateway $applicationGateway
     * @param EmailApplicationService $emailApplicationService
     * @param ApplicationSynchronisationService $applicationSynchronisationService
     */
    public function __construct(
        ApplicationGateway $applicationGateway,
        EmailApplicationService $emailApplicationService,
        ApplicationSynchronisationService $applicationSynchronisationService
    ) {
        $this->applicationSynchronisationService = $applicationSynchronisationService;
        $this->applicationGateway = $applicationGateway;
        $this->emailApplicationService = $emailApplicationService;
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

        $this->limitToOwnApplications($applications);
        $this->limitToInstallationOnMerchant($applications->where('installation_id', $installation));

        return $this->standardIndexAction(
            $applications->orderBy('updated_at', 'DESC'),
            'applications.index',
            'applications',
            [
                'default_dates' => $filterDates,
                'ext_current_status' => $this->fetchFilterValues($applications, 'ext_current_status'),
                'ext_finance_option_group' => $this->fetchFilterValues($applications, 'ext_finance_option_group'),
                'ext_merchant_liable_at' => $this->fetchNullFilterValues('Not Liable', 'Liable'),
                'export_custom_filename' => 'applications-export-' . date('Ymd-Hi'),
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
                'showDocuments' => $this->areDocumentsAvailable($application),
                'availableDocuments' => $this->getAvailableDocuments($application),
                'fulfilmentAvailable' => $this->isFulfilable($application),
                'cancellationAvailable' => $this->isCancellable($application),
                'partialRefundAvailable' => $this->canPartiallyRefund($application),
                'merchantPaymentsAvailable' => $this->canHaveMerchantPayments($application),
                'merchantPayments' => $this->applicationSynchronisationService->getRemoteMerchantPayments(
                    $application,
                    [
                        'count' => self::MERCHANT_PAYMENT_LIMIT,
                    ]
                ),
                'limit' => self::MERCHANT_PAYMENT_LIMIT,
                'applicationHistory' => array_reverse(
                    $this->applicationSynchronisationService->getApplicationHistory($application)
                ),
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
            '/installations/' . $installation . '/applications',
            $request
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
    public function fulfil($installation, $id, Request $request)
    {
        try {
            $this->applicationSynchronisationService->fulfil($id, $request->get('reference'));
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
     * @param ApplicationCancellationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function requestCancellation($installation, $id, ApplicationCancellationRequest $request)
    {
        try {
            $this->applicationSynchronisationService->requestCancellation($id, $request->get('description'));
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                '/installations/' . $installation . '/applications',
                'Failed to request cancellation',
                $e
            );
        }
        return $this->redirectWithSuccessMessage(
            '/installations/' . $installation . '/applications/' . $id,
            'Cancellation requested successfully'
        );
    }

    /**
     * Display pending cancellation list.
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

        return View(
            'applications.pending-cancellation',
            [
                'applications' => $pendingCancellations,
                'installation' => $installation,
            ]
        );
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
     * @param int $installation
     * @param int $id
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
     * @param int $installation
     * @return Application
     */
    private function fetchApplicationById($id, $installation)
    {
        return $this->fetchModelByIdWithInstallationLimit(
            (new Application()),
            $id,
            'application',
            'installations/' . $installation . '/applications'
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
     * @author WN, EB
     * @param Application $application
     * @return bool
     */
    private function isCancellable(Application $application)
    {
        return !in_array($application->ext_current_status, ['declined', 'pending_cancellation', 'cancelled']);
    }

    /**
     * @author SL
     * @param Application $application
     * @return bool
     */
    private function canHaveMerchantPayments(Application $application)
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
     * @author EB
     * @param int $installation
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function emailApplication($installation, $id)
    {
        $application = $this->fetchApplicationById($id, $installation);
        $this->sendApplicationEmail($application);

        return $this->redirectWithSuccessMessage(
            'installations/' . $installation . '/applications/' . $id,
            'Application successfully emailed to ' .
            (empty($application->ext_customer_email_address) ?
                $application->ext_applicant_email_address :
                $application->ext_customer_email_address)
        );
    }

    /**
     * @author EB
     * @param Application $application
     * @return Application
     * @throws RedirectException
     */
    private function sendApplicationEmail(Application $application)
    {
        try {
            $this->emailApplicationService->sendDefaultApplicationEmail(
                $application,
                TemplatesController::fetchDefaultTemplateForInstallation($application->installation),
                array_merge(
                    EmailTemplateEngine::getEmailTemplateFields($application),
                    $this->applicationSynchronisationService->getCreditInfoForApplication($application->id),
                    [
                        'template_footer' => $application->installation->getDefaultTemplateFooterAsHtml(),
                        'installation_name' => $application->installation->name,
                        'installation_logo' => $application->installation->custom_logo_url,
                        'apply_url' => $application->ext_resume_url,
                    ],
                    EmailConfigurationTemplateHelper::makeFromJson(
                        $application->installation->email_configuration
                    )->toArray()
                )
            );
            ApplicationEvent\ApplicationEventHelper::addEvent(
                $application,
                ApplicationEvent::TYPE_RESUME_EMAIL,
                Auth::user()
            );
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                'installations/' . $application->installation->id . '/applications/' . $application->id,
                'Unable to send Application via Email: ' . $e->getMessage(),
                $e
            );
        }

        return $application;
    }

    /**
     * @author SL
     *
     * @param Request $request
     * @param $installation
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function processAddMerchantPayment(Request $request, $installation, $id)
    {
        try {
            if (!$request->has(['effective_date', 'amount']) || !is_numeric($request->get('amount'))) {
                throw new Exception('Please ensure all required fields have been completed correctly!');
            }

            $amountPence = $request->get('amount') * 100;

            $response = $this->applicationSynchronisationService->addRemoteMerchantPayment(
                Application::find($id),
                Carbon::parse($request->get('effective_date')),
                $amountPence
            );

            if ($response) {
                return $this->redirectWithSuccessMessage(
                    'installations/' . $installation . '/applications/' . $id . '/',
                    'Successfully added merchant payment to application.'
                );
            }

            throw new Exception('An unknown error was encountered while trying to add the merchant payment.');
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                'installations/' . $installation . '/applications/' . $id . '/add-merchant-payment',
                'Unable to add merchant payment: ' . $e->getMessage(),
                $e
            );
        }
    }

    /**
     * @author EB
     * @param int $location
     * @param int $application
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function finishApplication($location, $application)
    {
        try {
            /** @var Location $location */
            $location = Location::findOrFail($location);
            $application = $this->fetchApplicationById($application, $location->installation->id);
            ApplicationEvent\ApplicationEventHelper::addEvent($application, ApplicationEvent::TYPE_RESUME_LINK);
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                'installations/' . $location->installation->id . '/applications',
                'Unable to complete the application: ' . $e->getMessage(),
                $e
            );
        }

        return $this->redirectWithSuccessMessage(
            'installations/' . $location->installation->id . '/applications/' . $application->id,
            'Successfully created an application'
        );
    }

    /**
     * @author SL
     *
     * @param int $installation
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function addMerchantPayment($installation, $id)
    {
        return view(
            'applications.merchant-payment',
            [
                'application' => Application::find($id),
            ]
        );
    }

    /**
     * Returns an array of fields and their types for filtering
     *
     * @author EB
     * @return array
     */
    protected function getFiltersConfiguration()
    {
        return [
            'ext_current_status' => Controller::FILTER_STRICT,
            'ext_order_amount' => Controller::FILTER_FINANCE,
            'ext_finance_loan_amount' => Controller::FILTER_FINANCE,
            'ext_finance_deposit' => Controller::FILTER_FINANCE,
            'ext_finance_subsidy' => Controller::FILTER_FINANCE,
            'ext_finance_net_settlement' => Controller::FILTER_FINANCE,
            'ext_merchant_liable_at' => Controller::FILTER_NULL,
        ];
    }

    /**
     * Returns if the pre-agreement and agreement documents are available for the application
     *
     * @author GK
     * @param Application $application
     * @return bool
     */
    private function areDocumentsAvailable($application)
    {
        return in_array(
            $application->ext_current_status,
            ['referred', 'converted', 'fulfilled', 'complete']
        );
    }

    /**
     * @author EB
     * @param Application $application
     * @return array
     */
    private function getAvailableDocuments(Application $application)
    {
        /** @var DocumentController $documentController */
        $documentController = App::make(DocumentController::class);

        return $documentController->getAvailableDocuments($application->installation->id, $application->id);
    }
}
