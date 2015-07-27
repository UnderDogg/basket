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
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * @return Response
     */
    public function index()
    {
        $messages = $this->getMessages();
        $installations = null;

        try {

            $installations = Installation::query();

            if (!empty($filter = $this->getTableFilter())) {
                foreach ($filter as $field => $query) {

                    $installations->where($field, 'like', '%' . $query . '%');
                }
                if (!$installations->count()) {
                    $messages['info'] = 'No records were found that matched your filter';
                }
            }

            $installations = $installations->paginate($this->getPageLimit());

        } catch (ModelNotFoundException $e) {

            $this->logError('Error occurred getting installations: ' . $e->getMessage());
            $messages['error'] = 'Error occurred getting installations';

        }

        return View('installations.index', ['installations' => $installations, 'messages' => $messages]);
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
            ['installations' => $this->fetchInstallation($id), 'messages' => $this->getMessages()]
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
            ['installations' => $this->fetchInstallation($id), 'messages' => $this->getMessages()]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     * @throws RedirectException
     */
    public function update($id, Request $request)
    {
        $installations = $this->fetchInstallation($id);
        try {
            $installations->update($request->all());
        } catch (\Exception $e) {
            $this->logError('Can not update installation [' . $id . ']: ' . $e->getMessage());
            throw (new RedirectException())->setTarget('/installations/' . $id . '/edit')->setError($e->getMessage());
        }

        return redirect()->back()->with('success', 'Installation details were successfully updated');
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
        return $this->fetchModelById((new Installation()), $id, 'installation', '/installations');
    }
}
