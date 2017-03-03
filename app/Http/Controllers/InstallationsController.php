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

use App\Basket\Email\EmailApplicationService;
use App\Basket\Email\EmailConfigurationTemplateHelper;
use App\Basket\Installation;
use App\Exceptions\RedirectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use PayBreak\Foundation\Properties\Bitwise;
use PayBreak\Sdk\Entities\GroupEntity;

/**
 * Class InstallationController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class InstallationsController extends Controller
{
    /** @var \App\Basket\Synchronisation\InstallationSynchronisationService  */
    private $installationSynchronisationService;

    protected $installationGateway;

    protected $productGateway;

    /**
     * @author WN
     */
    public function __construct()
    {
        $this->installationSynchronisationService = \App::make(
            'App\Basket\Synchronisation\InstallationSynchronisationService'
        );
        $this->installationGateway = \App::make(
            'PayBreak\Sdk\Gateways\InstallationGateway'
        );
        $this->productGateway = \App::make(
            'PayBreak\Sdk\Gateways\ProductGateway'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @author WN, MS
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $installations = Installation::query();
        $this->limitToMerchant($installations);
        return $this->standardIndexAction(
            $installations,
            'installations.index',
            'installations',
            [
                'linked' => $this->fetchBooleanFilterValues($installations, 'linked', 'Unlinked', 'Linked'),
                'active' => $this->fetchBooleanFilterValues($installations, 'active', 'Inactive', 'Active'),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     * @throws RedirectException
     */
    public function show($id)
    {
        return view(
            'installations.show',
            [
                'installations' => $this->fetchInstallation($id),
                'products' => $this->fetchProducts($id),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $installation = $this->fetchInstallation($id);

        return view(
            'installations.edit',
            [
                'installations' => $installation,
                'emailConfigHelper' => EmailConfigurationTemplateHelper::makeFromJson($installation->email_configuration),
                'bitwise' => Bitwise::make($installation->finance_offers),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param  int $id
     * @param Request $request
     * @return Response
     * @throws RedirectException
     */
    public function update($id, Request $request)
    {
        $this->amendValidityPeriod($request);

        $this->validate($request, [
            'name' => 'required|max:255',
            'active' => 'required|sometimes',
            'validity' => 'required|numeric|between:7200,2592000',
            'custom_logo_url' => 'url|max:255',
            'email_reply_to' => 'email|max:255',
            'ext_return_url' => 'url|max:255',
            'ext_notification_url' => 'url|max:255',
            'finance_offers' => 'required|integer',
        ]);

        $old = new Installation();
        $old = $old->findOrFail($id);

        try {
            $request->merge(['email_configuration' => $this->getEmailConfigurationFromParams($request)]);

            if ($old->ext_notification_url !== $request->ext_notification_url ||
                $old->ext_return_url !== $request->ext_return_url) {
                $this->installationGateway
                    ->patchInstallation(
                        $this->fetchInstallation($id)->ext_id,
                        [
                            'return_url' => $request->ext_return_url,
                            'notification_url' => $request->ext_notification_url
                        ],
                        $this->fetchInstallation($id)->merchant->token
                    );
            }
        } catch (\Exception $e) {
            throw RedirectException::make('/installations/' . $id . '/edit')->setError($e->getMessage());
        }

        return $this->updateModel((new Installation()), $id, 'installation', '/installations', $request);
    }

    /**
     * $fields = [
     *     'fieldName' => bool $required,
     * ]
     *
     * @author SL
     *
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    private function getEmailConfigurationFromParams(Request $request)
    {
        $fields = [
            'retailer_url',
            'retailer_telephone',
            'custom_colour_highlight',
            'custom_colour_button',
            'email_subject',
            'email_reply_to',
            'email_from_name',
        ];

        $rtn = [];

        foreach ($fields as $field) {
            try {
                $this->assertFieldExistsAndNotEmpty($request, $field);
            } catch (\Exception $e) {
                continue;
            }

            $rtn[$field] = $request->get($field);
        }

        return json_encode($rtn);
    }

    /**
     * @author SL
     * @param Request $request
     * @param string $field
     * @return bool
     * @throws \Exception
     */
    private function assertFieldExistsAndNotEmpty(Request $request, $field)
    {
        if (
            !(
                $request->has($field) &&
                !is_null($request->get($field)) &&
                strlen($request->get($field)) > 0
            )
        ) {
            throw new \Exception('Required field [' . $field . '] is missing or empty');
        }

        return true;
    }

    /**
     * @author WN
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function synchroniseAllForMerchant($id)
    {
        try {
            $this->installationSynchronisationService->synchroniseAllInstallations($id);
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                URL::previous(),
                'Error while trying to sync installations for merchant['.$id.']',
                $e
            );
        }
        return $this->redirectWithSuccessMessage(
            URL::previous(),
            'Synchronisation complete successfully'
        );
    }

    /**
     * @author EB, SL
     *
     * @param int $id
     * @return GroupEntity
     * @throws RedirectException
     */
    private function fetchProducts($id)
    {
        try {
            $installation = Installation::findOrFail($id);

            return $this->productGateway
                ->getProductGroupsWithProducts(
                    $installation->ext_id,
                    $installation->merchant->token
                );
        } catch (\Exception $e) {
            if ($e->getMessage() !== 'Products are empty') {
                throw $this->redirectWithException(URL::previous(), $e->getMessage(), $e);
            }

            return GroupEntity::make([]);
        }
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
            'id' => self::FILTER_STRICT,
            'merchant_id' => self::FILTER_STRICT,
        ];
    }

    /**
     * @author SL
     * @param string $id
     * @param EmailApplicationService $emailApplicationService
     * @param Request $request
     * @return string
     */
    public function previewEmail($id, EmailApplicationService $emailApplicationService, Request $request)
    {
        $installation = $this->fetchInstallation($id);
        $templateHelper = EmailConfigurationTemplateHelper::makeFromJson($installation->email_configuration);

        $name = ($templateHelper->has('retailer_name') ? $templateHelper->get('retailer_name') : $installation->name);

        try {
            return $emailApplicationService->getView(
                TemplatesController::fetchDefaultTemplateForInstallation($installation),
                array_merge(
                    [
                        'installation_logo' => $installation->custom_logo_url,
                        'customer_title' => 'Title',
                        'customer_first_name' => 'Forename',
                        'customer_last_name' => 'Surname',
                        'installation_name' => $name,
                        'order_description' => 'Example Order from ' . $name,
                        'payment_regular' => 0,
                        'apply_url' => '#',
                        'payments' => 0,
                        'order_amount' => 0,
                        'deposit_amount' => 0,
                        'loan_amount' => 0,
                        'total_repayment' => 0,
                        'offered_rate' => 0,
                        'apr' => 0,
                        'loan_cost' => 0,
                    ],
                    $templateHelper->toArray(),
                    $request->all()
                )
            );
        } catch (\Exception $e) {
            return view('emails.applications.blank')->with('content', 'Problem rendering template');
        }
    }

    /**
     * @author EB
     * @param Request $request
     * @return Request
     */
    private function amendValidityPeriod(Request $request)
    {
        $request->merge(['validity' => ($request->get('validity') * 24 * 60 * 60)]);

        return $request;
    }
}
