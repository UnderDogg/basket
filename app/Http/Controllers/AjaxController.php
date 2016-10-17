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
     * @author EB
     * @param Request $request
     * @param int $installation
     * @return array|\Illuminate\Http\Response
     */
    public function createProfilePersonal(Request $request, $installation)
    {
        $installation = Installation::findOrFail($installation)->first();

        try {
            return $this->profileGateway->createPersonal(
                $request->get('reference'),
                $request->all(),
                $installation->merchant->token
            );
        } catch (\Exception $e) {
            $this->logError('Create Profile Personal failed: ' . $e->getMessage(), $request->all());
            return $this->apiResponseFromException($e);
        }
    }
}
