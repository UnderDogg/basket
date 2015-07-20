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

use App\Http\Requests;
use App\Basket\Merchant;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class MerchantController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class MerchantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $messages = $this->getMessages();
        $merchants = null;

        try {

            $merchants = Merchant::query();
            $merchants = $merchants->paginate($this->getPageLimit());

        } catch (ModelNotFoundException $e) {

            $this->logError('Error occurred getting merchants: ' . $e->getMessage());
            $messages['error'] = 'Error occurred getting merchants';

        }

        return View('merchants.index', ['merchants' => $merchants, 'messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('merchants.create', ['messages' => $this->getMessages()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'token' => 'required',
        ]);

        $message = ['success','New Merchant has been successfully created'];

        try {

            Merchant::create($request->all());

        } catch (ModelNotFoundException $e) {

            $this->logError('Could not successfully create new Merchant' . $e->getMessage());
            $message = ['error','Could not successfully create new Merchant'];
        }

        return redirect('merchants')->with($message[0], $message[1]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $merchants = null;
        $messages = $this->getMessages();

        try {

            $merchants = Merchant::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not find Merchant with ID: [' . $id . ']; Merchant does not exist: ' . $e->getMessage()
            );
            $messages['error'] = 'Could not find Merchant with ID: [' . $id . ']; Merchant does not exist';
        }

        return view('merchants.show', ['merchants' => $merchants, 'messages' => $messages]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $merchants = null;
        $messages = $this->getMessages();

        try {

            $merchants = Merchant::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not get Merchant with ID [' . $id . '] for editing; Merchant does not exist:' . $e->getMessage()
            );
            $messages['error'] = 'Could not get Merchant with ID [' . $id . '] for editing; Merchant does not exist';
        }

        return view('merchants.edit', ['merchants' => $merchants, 'messages' => $messages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $message = ['success', 'Merchant details were successfully updated'];

        try {

            $merchants = Merchant::findOrFail($id);
            $merchants->update($request->all());

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not update Merchant with ID [' . $id . ']; Merchant does not exist' . $e->getMessage()
            );
            $message = ['error', 'Could not update Merchant with ID [' . $id . ']; Merchant does not exist'];
        }

        return redirect()->back()->with($message[0], $message[1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $message = ['success','Role was successfully deleted'];
        try {

            Merchant::destroy($id);

        } catch (ModelNotFoundException $e) {

            $this->logError('Deletion of this record did not complete successfully ' . $e->getMessage());
            $message = ['error', 'Deletion of this record did not complete successfully'];
        }

        return redirect('merchants')->with($message[0], $message[1]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function synchronise($id)
    {
        if (!$this->isMerchantAllowedForUser($id)) {

            return redirect('')->with('error', 'You are not allowed to take an action on this Merchant');
        }

        /** @var \App\Basket\Synchronisation\MerchantSynchronisationService $service */
        $service = \App::make('App\Basket\Synchronisation\MerchantSynchronisationService');

        try {
            $service->synchroniseMerchant($id);
            $message = ['success', 'Synchronisation complete successfully'];

        } catch (\Exception $e) {

            $this->logError('Error while trying to synchronise Merchant[' . $id . ']: ' . $e->getMessage());
            $message = ['error', 'Synchronisation not complete successfully'];
        }

        return redirect('merchants/' . $id)->with($message[0], $message[1]);
    }
}
