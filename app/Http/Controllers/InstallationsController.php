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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use PayBreak\Sdk\Entities\GroupEntity;
use Exception;

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
     * @throws Exception
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
        return view(
            'installations.edit',
            ['installations' => $this->fetchInstallation($id)]
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
        $this->validate($request, [
            'name' => 'required|max:255',
            'active' => 'required|sometimes',
            'validity' => 'required|integer|between:7200,604800',
            'custom_logo_url' => 'url|max:255',
            'ext_return_url' => 'url|max:255',
            'ext_notification_url' => 'url|max:255',
        ]);
        $old = new Installation();
        $old = $old->findOrFail($id);

        if($old->ext_notification_url !== $request->ext_notification_url ||
            $old->ext_return_url !== $request->ext_return_url) {
            try {
                $this->installationGateway
                    ->patchInstallation(
                        $this->fetchInstallation($id)->ext_id,
                        [
                            'return_url' => $request->ext_return_url,
                            'notification_url' => $request->ext_notification_url
                        ],
                        $this->fetchInstallation($id)->merchant->token
                    );
            } catch (\Exception $e) {
                dd($e);
                return RedirectException::make('/installations/' . $id . '/edit')->setError($e->getMessage());
            }
        }

        return $this->updateModel((new Installation()), $id, 'installation', '/installations', $request);
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
     * @author WN
     * @param int $id
     * @return Installation
     * @throws RedirectException
     */
    private function fetchInstallation($id)
    {
        return $this->fetchModelByIdWithMerchantLimit((new Installation()), $id, 'installation', '/installations');
    }

    /**
     * @author EB
     *
     * @param int $id
     * @return array|GroupEntity
     * @throws Exception
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
        } catch (Exception $e) {
            if ($e->getMessage() !== 'Products are empty') {
                throw $this->redirectWithException(URL::previous(), $e->getMessage(), $e);
            }

            return [];
        }
    }
}
