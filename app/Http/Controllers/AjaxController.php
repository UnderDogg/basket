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
use App\Basket\Location;
use Illuminate\Http\Request;
use PayBreak\Foundation\Exception;
use PayBreak\Sdk\Gateways\ProductGateway;
use PayBreak\Sdk\Gateways\ProfileGateway;

/**
 * Class AjaxController
 *
 * @package App\Http\Controllers
 * @author SL
 */
class AjaxController extends Controller
{
    /**
     * @var ProductGateway
     */
    private $productGateway;

    /**
     * @var ProfileGateway
     */
    private $profileGateway;

    /**
     * AjaxController constructor.
     *
     * @param ProductGateway $productGateway
     * @param ProfileGateway $profileGateway
     */
    public function __construct(ProductGateway $productGateway, ProfileGateway $profileGateway)
    {
        $this->productGateway = $productGateway;
        $this->profileGateway = $profileGateway;
    }

    /**
     * @author SL
     * @param Request $request
     * @param $installation
     * @param $product
     * @return array|\Illuminate\Http\Response
     */
    public function getCreditInformationForProduct(Request $request, $installation, $product)
    {
        try {
            if (!$request->has('deposit', 'order_amount')) {

                throw new Exception('Input field "deposit" and "order_amount" are both required.');
            }

            $inst = Installation::findOrFail(['ext_id' => $installation])->first();

            return $this->productGateway->getCreditInfo(
                $inst->ext_id,
                $product,
                $inst->merchant->token,
                [
                    'deposit_amount' => $request->input('deposit'),
                    'order_amount' => $request->input('order_amount'),
                ]
            );

        } catch (\Exception $e) {
            return $this->apiResponseFromException($e);
        }
    }

    /**
     * @author EA
     * @param Request $request
     * @param int $location
     * @return array|\Illuminate\Http\Response
     */
    public function addProfileAddress(Request $request, $location)
    {
        /** @var Location $location */
        $location = Location::findOrFail($location)->first();

        try {
            return $this->profileGateway->setAddress(
                (int) $request->get('user'),
                [
                    'abode' => (string) $request->get('abode'),
                    'building_name' => (string) $request->get('building_name'),
                    'building_number' => (string) $request->get('building_number'),
                    'street' => (string) $request->get('street'),
                    'locality' => (string) $request->get('locality'),
                    'town' => (string) $request->get('town'),
                    'postcode' => (string) $request->get('postcode'),
                    'moved_in' => (string) $request->get('moved_in'),
                    'residential_status' => (int) $request->get('residential_status'),
                ],
                $location->installation->merchant->token
            );
        } catch (\Exception $e) {
            $this->logError('Add address Failed: ' . $e->getMessage(), $request->all());
            return $this->apiResponseFromException($e, 422);
        }
    }

    /**
     * @author EA
     * @param Request $request
     * @param int $location
     * @return array|\Illuminate\Http\Response
     */
    public function setProfileEmployment(Request $request, $location)
    {
        /** @var Location $location */
        $location = Location::findOrFail($location)->first();

        try {
            return $this->profileGateway->setEmployment(
                (int) $request->get('user'),
                [
                    'employment_status' => (int) $request->get('employment_status'),
                    'employment_start' => (string)$request->get('employment_start'),
                    'phone_employer' => (string) $request->get('phone_employer'),
                ],
                $location->installation->merchant->token
            );
        } catch (\Exception $e) {
            $this->logError('Setting Employment Failed: ' . $e->getMessage(), $request->all());
            return $this->apiResponseFromException($e, 422);
        }
    }

    /**
     * @author EB
     * @param Request $request
     * @param $location
     * @return array|\Illuminate\Http\Response
     */
    public function setProfileFinancial(Request $request, $location)
    {
        /** @var Location $location */
        $location = Location::findOrFail($location)->first();

        try {
            return $this->profileGateway->setFinancial(
                $request->get('user'),
                [
                    'monthly_income' => (int) $request->get('monthly_income'),
                    'monthly_outgoings' => (int) $request->get('monthly_outgoings'),
                    'bank_sort_code' => (string) $request->get('bank_sort_code'),
                    'bank_account' => (string) $request->get('bank_account'),
                ],
                $location->installation->merchant->token
            );
        } catch (\Exception $e) {
            $this->logError('Set Profile Financial failed: ' . $e->getMessage(), $request->all());
            return $this->apiResponseFromException($e, 422);
        }
    }
}
