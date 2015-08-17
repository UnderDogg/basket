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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $merchants = Merchant::query();
        $this->limitToMerchant($merchants, 'id');
        return $this->standardIndexAction($merchants, 'merchants.index', 'merchants');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('merchants.create');
    }

    /**
     * Store a newly created resource in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'token' => 'required',
        ]);

        try {
            $merchant = Merchant::create($request->all());
            $this->merchantSynchronisationService->synchroniseMerchant($merchant->id, true);
            return $this->redirectWithSuccessMessage('/merchants/'.$id, 'New merchant has been successfully created');
        } catch (\Exception $e) {
            $this->logError('Could not successfully create new Merchant' . $e->getMessage());
            throw RedirectException::make('/merchants/')->setError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view(
            'merchants.show',
            ['merchants' => $this->fetchMerchantById($id)]
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
            'merchants.edit',
            ['merchants' => $this->fetchMerchantById($id)]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        return $this->destroyModel((new Merchant()), $id, 'merchant', '/merchants');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function synchronise($id)
    {
        try {
            $this->merchantSynchronisationService->synchroniseMerchant($id);
            return $this->redirectWithSuccessMessage('/merchants/'.$id, 'Synchronisation complete successfully');
        } catch (\Exception $e) {
            $this->logError('Error while trying to synchronise Merchant[' . $id . ']: ' . $e->getMessage());
            throw RedirectException::make('/merchants/'.$id)->setError($e->getMessage());
        }
    }
}
