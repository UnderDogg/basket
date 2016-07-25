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
use App\Basket\ApplicationEvent\ApplicationEventHelper;
use App\Basket\Installation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Basket\Location;
use App\Exceptions\RedirectException;
use Illuminate\Http\Request;
use PayBreak\Foundation\Properties\Bitwise;
use PayBreak\Sdk\Entities\Application\ApplicantEntity;
use PayBreak\Sdk\Entities\Application\OrderEntity;
use PayBreak\Sdk\Entities\Application\ProductsEntity;

/**
 * Initialisation Controller
 *
 * @author WN
 * @package App\Http\Controllers
 */
class InitialisationController extends Controller
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
     * @author WN
     * @param int $locationId
     * @return \Illuminate\View\View
     */
    public function prepare($locationId)
    {
        return view('initialise.main', [
            'location' => $this->fetchLocation($locationId),
        ]
        );
    }

    /**
     * @author WN, EB
     * @param $locationId
     * @param Request $request
     * @return $this|InitialisationController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws RedirectException
     */
    public function request($locationId, Request $request)
    {
        $this->validate(
            $request,
            [
                'amount' => 'required|integer',
                'group' => 'required',
                'product' => 'required',
                'reference' => 'required|min:6',
                'description' => 'required|min:6',
                'deposit' => 'sometimes|integer',
                'title' => 'sometimes|min:2|max:4',
                'first_name' => 'sometimes',
                'last_name' => 'sometimes',
                'applicant_email' => 'sometimes|email',
                'phone_home' => 'sometimes|max:11',
                'phone_mobile' => 'sometimes|max:11',
                'postcode' => 'sometimes|max:8',
            ]
        );

        try {
            return $this->applicationRequestType($this->fetchLocation($locationId), $request);
        } catch (\Exception $e) {
            $this->logError('Unable to request an Application: ' . $e->getMessage());
            throw RedirectException::make('/locations/' . $locationId . '/applications/make')
                ->setError('Unable to request an Application: ' . $e->getMessage());
        }
    }

    /**
     * @author EB
     * @param Request $request
     * @param Installation $installation
     * @return OrderEntity
     */
    private function createOrderEntity(Request $request, Installation $installation)
    {
        return OrderEntity::make([
            'reference' => $request->get('reference'),
            'amount' => (int) $request->get('amount'),
            'description' => $request->get('description'),
            'validity' => Carbon::now()->addSeconds($installation->validity)->toDateTimeString(),
            'deposit_amount' => $request->has('deposit') ? ($request->get('deposit') * 100) : $request->get('deposit'),
        ]);
    }

    /**
     * @author EB
     * @param Request $request
     * @return ProductsEntity
     */
    private function createProductsEntity(Request $request)
    {
        return ProductsEntity::make([
            'group' => $request->get('group'),
            'options' => [$request->get('product')],
        ]);
    }

    /**
     * @author EB
     * @param Request $request
     * @return ApplicantEntity
     */
    private function createApplicantEntity(Request $request)
    {
        return ApplicantEntity::make([
            'title' => $request->get('title'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email_address' => $request->get('applicant_email'),
            'phone_home' => $request->get('phone_home'),
            'phone_mobile' => $request->get('phone_mobile'),
            'postcode' => $request->get('postcode'),
        ]);
    }

    /**
     * @author EB
     * @param Location $location
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws RedirectException
     */
    private function applicationRequestType(Location $location, Request $request)
    {
        if($request->has('alternate')) {
            return view('initialise.alternate')
                ->with(
                    [
                        'input' => $request->only(
                            ['amount', 'product', 'product_name', 'group', 'reference', 'description', 'deposit']
                        ),
                        'bitwise' => Bitwise::make(($location->installation->finance_offers - Installation::IN_STORE)),
                        'location' => $location,
                    ]
                );
        }

        try {
            return $this->handleApplicationRequest($request, $location);
        } catch (\Exception $e) {
            $this->logError(
                'Failed to process an Application request: ' . $e->getMessage(),
                [
                    'request' => $request->all(),
                    'location' => $location->name,
                ]
            );
            throw $this->redirectWithException(
                '/installations/' . $location->installation->id . '/applications/make',
                'Failed to process the Application request',
                $e
            );
        }
    }

    /**
     * @author EB
     * @param Location $location
     * @param Request $request
     * @return \App\Basket\Application
     * @throws \App\Exceptions\Exception
     */
    private function createApplication(Location $location, Request $request)
    {
        return $this->applicationSynchronisationService->initialiseApplication(
            $location,
            $this->createOrderEntity($request, $location->installation),
            $this->createProductsEntity($request),
            $this->createApplicantEntity($request),
            $this->getAuthenticatedUser()
        );
    }

    /**
     * @author EB
     * @param Request $request
     * @param Location $location
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws RedirectException
     */
    private function handleApplicationRequest(Request $request, Location $location)
    {
        $application = $this->createApplication($location, $request);

        if ($request->has('link')) {
            ApplicationEventHelper::addEvent($application, ApplicationEvent::TYPE_RESUME_LINK, Auth::user());
            return $this->redirectWithSuccessMessage(
                '/installations/' . $location->installation->id . '/applications/' . $application->id,
                'Successfully created an Application. The Application\'s resume URL is: ' . $application->ext_resume_url
            );
        }

        if ($request->has('email')) {
            /** @var ApplicationsController $controller */
            $controller = \App::make('App\Http\Controllers\ApplicationsController');
            return $controller->emailApplication($location->installation->id, $application->id, $request);
        }

        ApplicationEventHelper::addEvent($application, ApplicationEvent::TYPE_RESUME_INSTORE, Auth::user());
        return redirect($application->ext_resume_url);
    }

    /**
     * @author EB
     * @param Location $location
     * @return string
     */
    private function generateOrderReferenceFromLocation(Location $location)
    {
        list($timeMid, $timeLow) = explode(' ', microtime());
        $reference = sprintf('%08x', $timeLow) . sprintf('%04x', (int)substr($timeMid, 2) & 0xffff);
        return $location->reference . '-' . $reference;
    }

    /**
     * @author WN
     * @param int $locationId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseProduct($locationId, Request $request)
    {
        $this->validate($request, ['amount' => 'required|numeric']);

        $location = $this->fetchLocation($locationId);

        /** @var \PayBreak\Sdk\Gateways\CreditInfoGateway $gateway */
        $gateway = \App::make('PayBreak\Sdk\Gateways\CreditInfoGateway');

        return view(
            'initialise.main',
            [
                'options' => $gateway->getCreditInfo(
                    $location->installation->ext_id,
                    floor($request->get('amount') * 100),
                    $location->installation->merchant->token
                ),
                'amount' => floor($request->get('amount') * 100),
                'location' => $location,
                'bitwise' => Bitwise::make($location->installation->finance_offers),
                'reference' => $this->generateOrderReferenceFromLocation($location),
            ]
        );
    }

    /**
     * @author SD
     * @return \Illuminate\View\View
     */
    public function returnBack()
    {
         return view('initialise.return_back');
    }

    /**
     * @author WN
     * @param $id
     * @return Location
     * @throws RedirectException
     */
    private function fetchLocation($id)
    {
        $location = $this->fetchModelByIdWithInstallationLimit((new Location()), $id, 'location', '/locations');

        if (!in_array($id,  $this->getAuthenticatedUser()->locations->pluck('id')->all())) {

            throw RedirectException::make('/')->setError('You don\'t have permissions to access this Location');
        }

        return $location;
    }
}
