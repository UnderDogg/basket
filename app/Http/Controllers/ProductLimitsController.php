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
use PayBreak\Sdk\Entities\GroupEntity;

/**
 * Class ProductLimitsController
 *
 * @author EB
 * @package App\Http\Controllers
 */
class ProductLimitsController extends Controller
{
    /** @var \PayBreak\Sdk\Gateways\ProductGateway */
    protected $productGateway;

    /**
     * @author EB
     */
    public function __construct()
    {
        $this->productGateway = \App::make(
            'PayBreak\Sdk\Gateways\ProductGateway'
        );
    }

    public function viewProducts($installation)
    {
        $installation = $this->fetchInstallation($installation);

        $products = $this->productGateway->getProductGroupsWithProducts($installation->ext_id, $installation->merchant->token);

        dd($installation->productLimits);

        $this->appendProductLimits($products);

        return view(
            'products.edit',
            [
                'installation' => $installation,
                'products' => $this->productGateway->getProductGroupsWithProducts($installation->ext_id, $installation->merchant->token),
                'limits' => $installation->productLimits,
            ]
        );
    }

    public function updateProducts($installation, Request $request)
    {
        $installation = $this->fetchInstallation($installation);

        dd($request->except('_token'));

    }

    private function getProductLimitsForInstallation(array $products)
    {
        foreach($products as $group) {
            dd($group->getProducts());
        }
    }
}
