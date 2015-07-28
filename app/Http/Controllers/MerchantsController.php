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
    /** @var  \App\Basket\Synchronisation\MerchantSynchronisationService */
    private $merchantSynchronisationService;

    public function __construct()
    {
        $this->merchantSynchronisationService = \App::make('App\Basket\Synchronisation\MerchantSynchronisationService');
    }

    /**
     * Display a listing of the resource.
     *
     * @author WN, MS
     * @return Response
     */
    public function index()
    {
        return $this->standardIndexAction(Merchant::query(), 'merchants.index', 'merchants');
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

            $merchant = Merchant::create($request->all());

            $this->merchantSynchronisationService->synchroniseMerchant($merchant->id, true);

            return redirect('merchants/' . $merchant->id)->with($message[0], $message[1]);

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
        return view(
            'merchants.show',
            ['merchants' => $this->fetchMerchantById($id), 'messages' => $this->getMessages()]
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
            'merchants.edit',
            ['merchants' => $this->fetchMerchantById($id), 'messages' => $this->getMessages()]
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
        return $this->updateModel((new Merchant()), $id, 'merchant', '/merchants', $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author WN
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->destroyModel((new Merchant()), $id, 'merchant', '/merchants');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function synchronise($id)
    {
        try {
            $this->merchantSynchronisationService->synchroniseMerchant($id);
            $message = ['success', 'Synchronisation complete successfully'];
        } catch (\Exception $e) {
            $this->logError('Error while trying to synchronise Merchant[' . $id . ']: ' . $e->getMessage());
            $message = ['error', 'Synchronisation not complete successfully'];
        }
        return redirect('merchants/' . $id)->with($message[0], $message[1]);
    }

    /**
     * @author WN
     * @param int $id
     * @return Merchant
     * @throws RedirectException
     */
    private function fetchMerchantById($id)
    {
        return $this->fetchModelById((new Merchant()), $id, 'merchant', '/merchants');
    }
}
