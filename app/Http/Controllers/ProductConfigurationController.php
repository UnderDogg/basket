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
 * Class ProductConfigurationController
 *
 * @author EB, EA
 * @package App\Http\Controllers
 */
class ProductConfigurationController extends Controller
{
    const UPDATE_PRODUCT_LIMIT = 'limits';
    const UPDATE_PRODUCT_ORDER = 'order';

    /** @var \PayBreak\Sdk\Gateways\ProductGateway */
    protected $productGateway;

    /**
     * @author EB
     */
    public function __construct()
    {
        $this->productGateway = \App::make(
            PayBreak\Sdk\Gateways\ProductGateway::class
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
                'grouped_products' => $this->fetchDefaultEditableProductSet($installation, true),
                'products' => $this->fetchDefaultEditableProductSet($installation, false),
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

        try {
            switch ($request->get('save', null)) {
                case self::UPDATE_PRODUCT_LIMIT:
                    self::updateProductLimits($installation, $request);
                    break;
                case self::UPDATE_PRODUCT_ORDER:
                    self::updateProductOrder($installation, $request);
                    break;
                default:
                    throw new \Exception('Save type not found');
            }
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                'installations/' . $installation->id . '/products',
                $e->getMessage(),
                $e
            );
        }

        return $this->viewProducts($installation->id)->with(
            'messages',
            ['success' => 'Successfully saved product ' . $request->get('save')]
        );
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
    private function storeProductLimit($id, Installation $installation, ProductEntity $product, $min, $max)
    {
        try {
            $limit = ProductLimit::where(['product' => $id, 'installation_id' => $installation->id])->first();
            if ($limit == null) {
                throw new ModelNotFoundException();
            }
        } catch (\Exception $e) {
            $limit = new ProductLimit();
        }

        $limit->installation_id = $installation->id;
        $limit->product = $product->getId();
        $limit->min_deposit_percentage = $min;
        $limit->max_deposit_percentage = $max;

        if (!$limit->save()) {
            throw new \Exception('Problem saving limit [' . $id . '] for Installation [' . $installation->id . ']');
        }

        return $limit;
    }

    /**
     * @author EB
     * @param Installation $installation
     * @param bool $grouped
     * @return GroupEntity
     */
    private function fetchDefaultEditableProductSet(Installation $installation, $grouped)
    {
        if ($grouped) {
            return  self::fetchGroupedProducts($installation);
        }

        return self::fetchProducts($installation);
    }

    /**
     * @author EA
     * @param Installation $installation
     * @param Request $request
     * @throws \Exception
     * @return \Illuminate\View\
     */
    public function updateProductOrder(Installation $installation, Request $request)
    {
        try {
            $products = [];
            $index = 0;

            foreach (explode(',', $request->get('product-order')) as $product) {
                $products['products'][$product] = $index++;
            }

            $this->productGateway->orderProducts(
                $installation->ext_id,
                $installation->merchant->token,
                $products
            );
        } catch (\Exception $e) {
            throw new \Exception('Unable to save product order');
        }
    }

    /**
     * @author EB, EA
     * @param Installation $installation
     * @return array
     */
    private function fetchGroupedProducts($installation)
    {

        /** @var GroupEntity[] $groups */
        $groups = $this->productGateway->getProductGroupsWithProducts(
            $installation->ext_id,
            $installation->merchant->token
        );

        foreach ($groups as $group) {
            $products = $group->getProducts();
            foreach ($products as $key => &$product) {
                if ($product->getDeposit()->getMinimumPercentage() == null &&
                    $product->getDeposit()->getMaximumPercentage() == null) {
                    unset($products[$key]);
                }
            }
            $group->setProducts($products);
        }

        return $groups;
    }


    /**
     * @author EA
     * @param Installation $installation
     * @return array
     */
    private function fetchProducts($installation)
    {
        /** @var ProductEntity[] $groups */
        return $this->productGateway->getProducts(
            $installation->ext_id,
            $installation->merchant->token
        );
    }

    /**
     * author EA, EB
     * @param $installation
     * @param Request $request
     * @throws \Exception
     */
    private function updateProductLimits($installation, Request $request)
    {
        /** @var \PayBreak\Sdk\Entities\GroupEntity[] $groups */
        $groups = $this->fetchDefaultEditableProductSet($installation, true);

        $limits = $request->except('_token');

        foreach ($groups as $group) {
            foreach ($group->getProducts() as $product) {
                try {
                    if ($request->has('min-' . $product->getId()) && $request->has('max-' . $product->getId())) {
                        $min = (float)$limits['min-' . $product->getId()];
                        $max = (float)$limits['max-' . $product->getId()];

                        if ($min >= $product->getDeposit()->getMinimumPercentage() &&
                            $max <= $product->getDeposit()->getMaximumPercentage()
                        ) {
                            $this->storeProductLimit($product->getId(), $installation, $product, $min, $max);
                        }
                    }
                } catch (\Exception $e) {
                    throw new \Exception('Unable to save product limit');
                }
            }
        }
    }
}
