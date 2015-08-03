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
use App\Exceptions\RedirectException;
use Illuminate\Http\Request;
use PayBreak\Sdk\Entities\ApplicationEntity;

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

        list($timeMid, $timeLow) = explode(' ', microtime());
        $reference = sprintf('%08x', $timeLow) . '-' . sprintf('%04x', (int)substr($timeMid, 2) & 0xffff);

        return view(
            'initialise.confirm',
            [
                'amount' => $request->get('amount'),
                'group' => $request->get('group'),
                'product' => $request->get('product'),
                'reference' => $reference,
                'location' => $locationId,
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

        /** @var \PayBreak\Sdk\Gateways\ApplicationGateway $gateway */
        $gateway = \App::make('PayBreak\Sdk\Gateways\ApplicationGateway');

        $location = $this->fetchModelByIdWithInstallationLimit((new Location()), $locationId, 'location', '/locations');

        $application = ApplicationEntity::make(
            [
                'installation' => $location->installation->ext_id,
                'order' => [
                    'reference' => $request->get('reference'),
                    'amount' => (int) $request->get('amount'),
                    'description' => $request->get('description'),
                    'validity' => 'tomorrow 18:00',
                ],
                'products' => [
                    'group' => $request->get('group'),
                    'options' => [$request->get('product')],
                ],
            ]
        );

        try {
            $application = $gateway->initialiseApplication($application, $location->installation->merchant->token);

            return redirect($application->getResumeUrl());

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
