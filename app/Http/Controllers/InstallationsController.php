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
use App\Basket\Installation;
use Illuminate\Http\Request;
use PayBreak\Sdk\Gateways\InstallationGateway;

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

    /**
     * @author WN
     * @param InstallationGateway $installationGateway
     */
    public function __construct(InstallationGateway $installationGateway)
    {
        $this->installationSynchronisationService = \App::make(
            'App\Basket\Synchronisation\InstallationSynchronisationService'
        );
        $this->installationGateway = $installationGateway;
    }

    /**
     * Display a listing of the resource.
     *
     * @author WN, MS
     * @return Response
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
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view(
            'installations.show',
            ['installations' => $this->fetchInstallation($id)]
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
            'validity' => 'required|integer|between:7200,604800',
        ]);
        $apiValues =
            [
                'return_url' => $request->ext_return_url,
                'notification_url' => $request->ext_notification_url,
            ];
        $this->installationGateway
            ->patchInstallation(
                $this->fetchInstallation($id)->ext_id,
                $apiValues,
                $this->fetchInstallation($id)->merchant->token
            );
        return $this->updateModel((new Installation()), $id, 'installation', '/installations', $request);
    }

    /**
     * @author WN
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws InstallationsController
     */
    public function synchroniseAllForMerchant($id)
    {
        try {
            $this->installationSynchronisationService->synchroniseAllInstallations($id);
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                '/merchants/'.$id,
                'Error while trying to sync installations for merchant['.$id.']',
                $e
            );
        }
        return $this->redirectWithSuccessMessage(
            '/merchants/'.$id,
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
}
