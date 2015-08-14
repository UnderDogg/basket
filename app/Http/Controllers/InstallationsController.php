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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

    /**
     * @author WN
     */
    public function __construct()
    {
        $this->installationSynchronisationService = \App::make(
            'App\Basket\Synchronisation\InstallationSynchronisationService'
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
        $installations = Installation::query();
        $this->limitToMerchant($installations);
        return $this->standardIndexAction($installations, 'installations.index', 'installations');
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
        return $this->updateModel((new Installation()), $id, 'installation', '/installations', $request);
    }

    /**
     * @author WN
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function synchroniseAllForMerchant($id)
    {
        try {
            $this->installationSynchronisationService->synchroniseAllInstallations($id);
            $message = ['success', 'Synchronisation complete successfully'];

        } catch (\Exception $e) {

            $this->logError(
                'Error while trying to synchronise Installations for Merchant[' .
                $id . ']: ' . $e->getMessage()
            );
            $message = ['error', 'Synchronisation not complete successfully'];
        }

        return redirect('merchants/' . $id)->with($message[0], $message[1]);
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
