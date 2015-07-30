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

use App\Basket\Location;
use Illuminate\Http\Request;

/**
 * Initialisation Controller
 *
 * @author WN
 * @package App\Http\Controllers
 */
class InitialisationController extends Controller
{
    /**
     * @author WN
     * @param int $locationId
     * @return \Illuminate\View\View
     */
    public function prepare($locationId)
    {
        return view('initialise.main');
    }

    public function initialise($locationId, Request $request)
    {

    }

    /**
     * @author WN
     * @param int $locationId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditInfo($locationId, Request $request)
    {
        $this->validate($request, ['amount' => 'required']);

        $location = $this->fetchModelByIdWithInstallationLimit((new Location()), $locationId, 'location', '/locations');

        /** @var \App\Basket\Gateways\CreditInfoGateway $gateway */
        $gateway = \App::make('App\Basket\Gateways\CreditInfoGateway');

        return response()->json(
            $gateway->getCreditInfo(
                $location->installation->ext_id,
                $request->get('amount'),
                $location->installation->merchant->token
            ),
            200
        );
    }
}
