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
use App\Basket\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Basket\Location;
use App\Exceptions\RedirectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PayBreak\Foundation\Properties\Bitwise;
use PayBreak\Sdk\Entities\Application\ApplicantEntity;
use PayBreak\Sdk\Entities\Application\OrderEntity;
use PayBreak\Sdk\Entities\Application\ProductsEntity;
use App\Basket\Synchronisation\ApplicationSynchronisationService;
use PayBreak\Sdk\Entities\ProductEntity;
use PayBreak\Sdk\Gateways\CreditInfoGateway;
use PayBreak\Sdk\Gateways\ProductGateway;
use PayBreak\Sdk\Gateways\DictionaryGateway;
use PayBreak\Sdk\Gateways\ProfileGateway;

/**
 * Initialisation Controller
 *
 * @author WN
 * @package App\Http\Controllers
 */
class InitialisationController extends Controller
{
    const PRODUCT_GROUP_FLEXIBLE_FINANCE = 'FF';

    /**
     * @var ApplicationSynchronisationService
     */
    private $applicationSynchronisationService;
    /**
     * @var CreditInfoGateway
     */
    private $creditInfoGateway;
    /**
     * @var ProductGateway
     */
    private $productGateway;
    /**
     * @var DictionaryGateway
     */
    private $dictionaryGateway;
    /**
     * @var ProfileGateway
     */
    private $profileGateway;

    /**
     * Initialisation Controller constructor.
     *
     * @author EB
     * @param ApplicationSynchronisationService $applicationSynchronisationService
     * @param CreditInfoGateway $creditInfoGateway
     * @param ProductGateway $productGateway
     * @param DictionaryGateway $dictionaryGateway
     * @param ProfileGateway $profileGateway
     */
    public function __construct(
        ApplicationSynchronisationService $applicationSynchronisationService,
        CreditInfoGateway $creditInfoGateway,
        ProductGateway $productGateway,
        DictionaryGateway $dictionaryGateway,
        ProfileGateway $profileGateway
    ) {
        $this->applicationSynchronisationService = $applicationSynchronisationService;
        $this->creditInfoGateway = $creditInfoGateway;
        $this->productGateway = $productGateway;
        $this->dictionaryGateway = $dictionaryGateway;
        $this->profileGateway = $profileGateway;
    }

    /**
     * @author WN
     * @param int $locationId
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function prepare($locationId)
    {
        return view('initialise.main',
            [
                'location' => $this->validateApplicationCanBeMade($this->fetchLocation($locationId)),
            ]
        );
    }

    /**
     * @author WN, EB
     * @param $locationId
     * @param Request $request
     * @return $this|InitialisationController|\Illuminate\Http\RedirectResponse|Redirect
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
                'email' => 'sometimes|email|max:255',
                'phone_home' => 'sometimes|max:11',
                'phone_mobile' => 'sometimes|max:11',
                'postcode' => 'sometimes|max:8',
            ]
        );

        $location = $this->fetchLocation($locationId);
        $this->validateApplicationRequest($request, $location);

        try {
            return $this->applicationRequestType($location, $request);
        } catch (\Exception $e) {
            $this->logError('Unable to request an Application: ' . $e->getMessage());
            throw RedirectException::make('/locations/' . $locationId . '/applications/make')
                ->setError('Failed to process the Application, please try again');
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
     * @return $this|\Illuminate\Http\RedirectResponse|Redirect
     * @throws RedirectException
     */
    private function applicationRequestType(Location $location, Request $request)
    {
        if ($request->has('assisted') && !$request->has('email')) {
            return view('initialise.assisted')
                ->with([
                    'input' => $request->only(
                        ['amount', 'product', 'product_name', 'group', 'reference', 'description', 'deposit']
                    ),
                    'location' => $location,
                ]);
        }

        try {
            return $this->handleApplicationRequest($request, $location);
        } catch (RedirectException $e) {
            throw $e;
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
     * @param Location $location
     * @param Request $request
     * @return Application
     * @throws \App\Exceptions\Exception
     */
    private function createAssistedApplication(Location $location, Request $request)
    {
        return $this->applicationSynchronisationService->initialiseAssistedApplication(
            $request->get('email'),
            $location,
            $this->createOrderEntity($request, $location->installation),
            $this->createProductsEntity($request),
            $this->createApplicantEntity($request),
            $this->getAuthenticatedUser()
        );
    }

    /**
     * @author EB
     * @param int $id
     * @return View
     */
    public function noFinance($id)
    {
        $location = $this->fetchLocation($id);
        return view('initialise.no_finance')->with(['location' => $location]);
    }

    /**
     * @author EB
     * @param Request $request
     * @param Location $location
     * @return \Illuminate\Http\RedirectResponse|Redirect
     * @throws \Exception
     */
    private function handleApplicationRequest(Request $request, Location $location)
    {
        if ($request->has('assisted')) {
            try {
                $application = $this->createAssistedApplication($location, $request);
            } catch (\Exception $e) {
                return Redirect('/locations/' . $location->id . '/no-finance');
            }

            if (is_null($application->ext_user)) {
                $this->createProfilePersonal($request, $location, $application);
                return redirect('/locations/' . $location->id . '/applications/' . $application->id . '/profile');
            }

            $this->applicationSynchronisationService->synchroniseApplication($application->id);
            return $this->redirectWithSuccessMessage(
                '/installations/' . $location->installation->id . '/applications/' . $application->id,
                'Application successfully created. You can action on this in the `Application / Resume Link` section below'
            );
        }

        $application = $this->createApplication($location, $request);

        if ($request->has('email')) {
            return redirect(
                '/installations/' . $location->installation->id . '/applications/' . $application->id . '/email'
            );
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
     * @param bool $assisted
     * @return \Illuminate\Http\JsonResponse
     * @throws RedirectException
     */
    public function chooseProduct($locationId, Request $request, $assisted = false)
    {
        $this->validate($request, ['amount' => 'required|numeric']);
        $location = $this->fetchLocation($locationId);

        return view('initialise.main')->with(
            [
                'options' => $this->getCreditInfoWithProductLimits(
                    $location->installation,
                    $request->get('amount') * 100
                ),
                'flexibleFinance' => $this->prepareFlexibleFinance($location, $request->get('amount') * 100),
                'amount' => floor($request->get('amount') * 100),
                'location' => $location,
                'bitwise' => Bitwise::make($location->installation->finance_offers),
                'reference' => $this->generateOrderReferenceFromLocation($location),
                'assisted' => $assisted,
            ]
        );
    }

    /**
     * @param Location $location
     * @param int $orderAmount
     * @return array
     * @author SL
     */
    private function prepareFlexibleFinance(Location $location, $orderAmount)
    {
        try {
            $products = $this->productGateway->getProductsInGroup(
                $location->installation->ext_id,
                self::PRODUCT_GROUP_FLEXIBLE_FINANCE,
                $location->installation->merchant->token
            );

            $filteredProducts = [];

            /** @var ProductEntity $product */
            foreach ($products as $product) {
                if (
                    $product->getOrder()->getMinimumAmount() <= $orderAmount &&
                    $product->getOrder()->getMaximumAmount() >= $orderAmount
                ) {
                    $filteredProducts[] = $product;
                }
            }

            return $filteredProducts;

        }catch(\Exception $e) {
            return [];
        }
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

            throw RedirectException::make('/')->setError('You don\'t have permission to access this Location');
        }

        return $location;
    }

    /**
     * @author EB
     * @param Installation $installation
     * @param $amount
     * @return array
     */
    public function getCreditInfoWithProductLimits(Installation $installation, $amount)
    {
        $limits = $this->fetchInstallationProductLimits($installation);

        $creditInfo = $this->creditInfoGateway->getCreditInfo(
            $installation->ext_id,
            floor($amount),
            $installation->merchant->token
        );

        if (count($limits) > 0) {
            $creditInfo = $this->getRestrictedDepositLimitsForProducts($creditInfo, $limits, $installation, $amount);
        }

        return $creditInfo;
    }

    /**
     * @author EB
     * @param array $creditInfo
     * @param array $limits
     * @param Installation $installation
     * @param $amount
     * @return array
     */
    public function getRestrictedDepositLimitsForProducts(array $creditInfo, array $limits, Installation $installation, $amount)
    {
        foreach ($creditInfo as &$group) {
            foreach ($group['products'] as &$product) {

                if (array_key_exists($product['id'], $limits)) {

                    $min = (int) max($product['deposit']['minimum_percentage'], $limits[$product['id']]['min_deposit_percentage']);
                    $max = (int) min($product['deposit']['maximum_percentage'], $limits[$product['id']]['max_deposit_percentage']);

                    if ($min > $max) {
                        unset($product);
                        continue;
                    }

                    $product['deposit']['minimum_percentage'] = $min;
                    $product['deposit']['maximum_percentage'] = $max;

                    $local = $this->productGateway->getCreditInfo(
                        $installation->ext_id,
                        $product['id'],
                        $installation->merchant->token,
                        [
                            'deposit_amount' => floor($amount * ($min / 100)),
                            'order_amount' => floor($amount),
                        ]
                    );

                    $product['credit_info'] = $local;

                    $product['credit_info']['deposit_range']['minimum_amount'] = max(
                        floor(($amount) * ($min / 100)),
                        $product['credit_info']['deposit_range']['minimum_amount']
                    );
                    $product['credit_info']['deposit_range']['maximum_amount'] = min(
                        floor(($amount) * ($max / 100)),
                        $product['credit_info']['deposit_range']['maximum_amount']
                    );
                }
            }
        }

        return $creditInfo;
    }

    /**
     * @author EB
     * @param Location $location
     * @return Location
     * @throws RedirectException
     */
    private function validateApplicationCanBeMade(Location $location)
    {
        if($location->installation->finance_offers > 0) {
            return $location;
        }

        throw $this->redirectWithException(
            '/',
            'Cannot make an application for location [' . $location->id . ']',
            new \Exception('There is no valid finance route for installation [' . $location->installation->id . ']')
        );
    }

    /**
     * @author EB
     * @param Request $request
     * @param Location $location
     * @return bool
     * @throws RedirectException
     */
    private function validateApplicationRequest(Request $request, Location $location)
    {
        /** @var Application $application */
        if($application = Application::where('ext_order_reference' , '=', $request->get('reference'))
            ->where('installation_id', '=', $location->installation->id)->first()) {
            throw RedirectException::make('/locations/' . $location->id . '/applications/make')
                ->setError('Unable to process the request, an application has already been created with this order
                reference (<a href="/installations/' . $location->installation->id . '/applications/' . $application->id
                . '">' . $application->ext_order_reference . '</a>)');
        }

        return true;
    }

    /**
     * @param int $location
     * @param int $application
     * @return View
     * @throws RedirectException
     */
    public function showProfile($location, $application)
    {
        try {
            $locationObj = $this->fetchLocation($location);
            $applicationObj = $this->fetchApplicationDetails($application, 'id');
            $this->checkIfProfileCanBeEdited($applicationObj);
            $dictionaries = $this->fetchDictionaries($locationObj->installation->merchant);
        } catch (\Exception $e) {
            $this->logError('Profile creation failed: ' . $e->getMessage() . ' trace[' . $e->getTraceAsString() . ']');
            throw $this->redirectWithException('/', 'Profile Creation Failed: ' . $e->getMessage(), $e);
        }

        return view('initialise.profile')->with(
            array_merge(
                [
                    'application' => $applicationObj,
                    'location' => $locationObj,
                    'user' => $applicationObj->ext_user,
                ],
                $dictionaries
            )
        );
    }

    /**
     * @author EB
     * @param Merchant $merchant
     * @return array
     */
    private function fetchDictionaries(Merchant $merchant)
    {
        return [
            'employmentStatuses' => $this->dictionaryGateway->getEmploymentStatuses($merchant->token),
            'maritalStatuses' => $this->dictionaryGateway->getMaritalStatuses($merchant->token),
            'residentialStatuses' => $this->dictionaryGateway->getResidentialStatuses($merchant->token),
        ];
    }

    /**
     * @author EB
     * @param Request $request
     * @param Location $location
     * @param Application $application
     * @return Application
     * @throws \Exception
     */
    private function createProfilePersonal(Request $request, Location $location, Application $application)
    {
        $this->profileGateway->createPersonal(
            $application->ext_id,
            [
                'first_name' => (string)$request->get('first_name'),
                'last_name' => (string)$request->get('last_name'),
                'phone_mobile' => (string)$request->get('phone_mobile'),
                'phone_home' => (string)$request->get('phone_home'),
            ],
            $location->installation->merchant->token
        );

        return $this->applicationSynchronisationService->synchroniseApplication($application->id);
    }

    /**
     * @author EB
     * @param Application $application
     * @return bool
     * @throws \Exception
     */
    private function checkIfProfileCanBeEdited(Application $application)
    {
        if ($application->ext_current_status == 'initialized') {
            throw new \Exception('You cannot edit the profile as the application is not initialized');
        }

        return true;
    }
}
