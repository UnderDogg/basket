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
use App\Basket\ProductLimit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use PayBreak\Sdk\Entities\GroupEntity;
use PayBreak\Sdk\Entities\ProductEntity;

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

    /**
     * @author EB
     * @param int $installation
     * @return \Illuminate\View\View
     */
    public function viewProducts($installation)
    {
        $installation = $this->fetchInstallation($installation);

        return view(
            'products.edit',
            [
                'installation' => $installation,
                'products' => $this->fetchDefaultEditableProductSet($installation),
                'limits' => $this->fetchInstallationProductLimits($installation),
            ]
        );
    }

    /**
     * @author EB
     * @param int $installation
     * @param Request $request
     * @return \Illuminate\View\View
     * @throws \App\Exceptions\RedirectException
     */
    public function updateProducts($installation, Request $request)
    {
        $installation = $this->fetchInstallation($installation);

        /** @var \PayBreak\Sdk\Entities\GroupEntity[] $groups */
        $groups = $this->fetchDefaultEditableProductSet($installation);

        $limits = $request->except('_token');

        foreach($groups as $group) {
            foreach($group->getProducts() as $product) {
                try {
                    $min = $limits['min-' . $product->getId()];
                    $max = $limits['max-' . $product->getId()];

                    $this->storeProductLimit($product->getId(), $installation, $product, $min, $max);
                } catch (\Exception $e) {
                    throw $this->redirectWithException(
                        'installations/' . $installation->id . '/products',
                        'Unable to save product limit',
                        $e
                    );
                }
            }
        }

        return $this->viewProducts($installation->id)->with('messages', ['success' => 'Successfully saved product limits']);

    }

    /**
     * @authpr EB
     * @param string $id
     * @param Installation $installation
     * @param ProductEntity $product
     * @param float $min
     * @param float $max
     * @return ProductLimit
     * @throws \Exception
     */
    public function storeProductLimit($id, Installation $installation, ProductEntity $product, $min, $max)
    {
        try {
            $limit = ProductLimit::where(['product' => $id, 'installation_id' => $installation->id])->first();
            if($limit == null) throw new ModelNotFoundException();
        } catch (\Exception $e) {
            $limit = new ProductLimit();
        }

        $limit->installation_id = $installation->id;
        $limit->product = $product->getId();
        $limit->min_deposit_percentage = $min;
        $limit->max_deposit_percentage = $max;

        if(!$limit->save()) {
            throw new \Exception('Problem saving limit [' . $id . '] for Installation [' . $installation->id . ']');
        }

        return $limit;
    }

    /**
     * @author EB
     * @param Installation $installation
     * @return GroupEntity
     */
    private function fetchDefaultEditableProductSet(Installation $installation)
    {
        $groups = $this->productGateway->getProductGroupsWithProducts(
            $installation->ext_id,
            $installation->merchant->token
        );

        foreach($groups as $group) {
            $products = $group->getProducts();
            foreach($products as $key => &$product) {
                if($product->getDeposit()->getMinimumPercentage() == null && $product->getDeposit()->getMaximumPercentage() == null) {
                    unset($products[$key]);
                }
            }
            $group->setProducts($products);
        }

        return $groups;
    }
}
