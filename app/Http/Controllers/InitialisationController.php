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

    public function confirm($locationId, Request $request)
    {



        return response()->json($request->all(), 200);
    }

    /**
     * @author WN
     * @param int $locationId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function choseProduct($locationId, Request $request)
    {
        $this->validate($request, ['amount' => 'required']);

        $location = $this->fetchModelByIdWithInstallationLimit((new Location()), $locationId, 'location', '/locations');

        /** @var \PayBreak\Sdk\Gateways\CreditInfoGateway $gateway */
        $gateway = \App::make('PayBreak\Sdk\Gateways\CreditInfoGateway');

        return view(
            'initialise.main',
            [
                'options' => $gateway->getCreditInfo(
                    $location->installation->ext_id,
                    $request->get('amount') * 100,
                    $location->installation->merchant->token
                ),
                'amount' => $request->get('amount') * 100,
                'location' => $locationId,
            ]
        );
    }
}
