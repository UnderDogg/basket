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
use Illuminate\Support\Facades\URL;

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
            'name' => 'required|max:255',
            'token' => 'required|min:32|max:32',
        ]);


        $this->validateMerchantToken($request->only(['token'])['token'],$request->only(['name'])['name']);
        try {
            $merchant = Merchant::create($request->all());
            $this->merchantSynchronisationService->synchroniseMerchant($merchant->id, true);
        } catch (\Exception $e) {
            $this->logError('Could not successfully create new Merchant' . $e->getMessage());
            throw RedirectException::make('/merchants/')->setError($e->getMessage());
        }
        return $this->redirectWithSuccessMessage(
            '/merchants/'.$merchant->id,
            'New merchant has been successfully created'
        );
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
        $this->validate($request, [
            'name' => 'required|max:255',
            'token' => 'required|min:32|max:32',
            'active' => 'required|sometimes',
        ]);

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
            $this->merchantSynchronisationService->synchroniseMerchant($id, true);
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                URL::previous(),
                'Error while trying to synchronise Merchant[' . $id . ']',
                $e)
            ;
        }

        return $this->redirectWithSuccessMessage(
            URL::previous(),
            'Synchronisation complete successfully'
        );
    }

    /**
     * Checks for token duplication in the database,returns true if there is no duplication detected.
     *
     * @author EA
     * @param $token
     * @param $merchantName
     * @return bool
     * @throws RedirectException
     */
    public function validateMerchantToken($token, $merchantName)
    {
        $duplicatedTokens = Merchant::all()->where('token', $token);
        if(!$duplicatedTokens->isEmpty()){
            $this->logError('Cannot create merchant['.$merchantName.'] merchant:Merchant token already exist in database');
            throw RedirectException::make('/merchants')
                ->setError('Invalid merchant token ');
        }
        return true;
    }
}
