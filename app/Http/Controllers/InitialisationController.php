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
use Illuminate\Support\Facades\Auth;
use App\Basket\Location;
use App\Exceptions\RedirectException;
use Illuminate\Http\Request;

/**
 * Initialisation Controller
 *
 * @author WN
 * @package App\Http\Controllers
 */
class InitialisationController extends Controller
{
    /** @var \App\Basket\Synchronisation\ApplicationSynchronisationService */
    private $applicationSynchronisationService;

    public function __construct()
    {
        $this->applicationSynchronisationService = \App::make(
            'App\Basket\Synchronisation\ApplicationSynchronisationService'
        );
    }

    /**
     * @author WN
     * @param int $locationId
     * @return \Illuminate\View\View
     */
    public function prepare($locationId)
    {
        $this->fetchLocation($locationId);

        return view('initialise.main');
    }

    /**
     * @author WN
     * @param $locationId
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function confirm($locationId, Request $request)
    {
        $this->validate(
            $request,
            [
                'amount' => 'required|integer',
                'group' => 'required',
                'product' => 'required',
            ]
        );

        $location = $this->fetchLocation($locationId);

        list($timeMid, $timeLow) = explode(' ', microtime());
        $reference = sprintf('%08x', $timeLow) . sprintf('%04x', (int)substr($timeMid, 2) & 0xffff);

        $reference = $location->reference . '-' . $reference;

        return view(
            'initialise.confirm',
            [
                'amount' => $request->get('amount'),
                'group' => $request->get('group'),
                'product' => $request->get('product'),
                'reference' => $reference,
                'location' => $location,
            ]
        );
    }

    /**
     * @author WN
     * @param $locationId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws RedirectException
     */
    public function request($locationId, Request $request)
    {
        $this->validate(
            $request,
            [
                'amount' => 'required',
                'group' => 'required',
                'product' => 'required',
                'description' => 'required',
                'reference' => 'required',
            ]
        );

        $requester = Auth::user()->id;
        $location = $this->fetchLocation($locationId);

        try {
            return redirect($this->applicationSynchronisationService->initialiseApplication(
                $location->installation->id,
                $request->get('reference'),
                $request->get('amount'),
                $request->get('description'),
                $location->installation->validity,
                $request->get('group'),
                [$request->get('product')],
                $location,
                $requester
            ));
        } catch (\Exception $e) {

            throw RedirectException::make('/locations/' . $locationId . '/applications/make')
                ->setError($e->getMessage());
        }
    }

    /**
     * @author WN
     * @param int $locationId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseProduct($locationId, Request $request)
    {
        $this->validate($request, ['amount' => 'required|numeric']);

        $location = $this->fetchLocation($locationId);

        /** @var \PayBreak\Sdk\Gateways\CreditInfoGateway $gateway */
        $gateway = \App::make('PayBreak\Sdk\Gateways\CreditInfoGateway');

        return view(
            'initialise.main',
            [
                'options' => $gateway->getCreditInfo(
                    $location->installation->ext_id,
                    floor($request->get('amount') * 100),
                    $location->installation->merchant->token
                ),
                'amount' => $request->get('amount') * 100,
                'location' => $locationId,
            ]
        );
    }

    /**
     * @author SD
     * @return \Illuminate\View\View
     */
    public function returnBack()
    {
         return view('initialise.returnBack');
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

            throw RedirectException::make('/')->setError('You don\'t have permissions to access this Location');
        }

        return $location;
    }
}
