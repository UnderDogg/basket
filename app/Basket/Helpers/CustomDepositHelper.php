<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Basket\Helpers;

/**
 * Class CustomDepositHelper
 *
 * @package App\Basket\Helpers
 * @author SL
 */
class CustomDepositHelper
{
    /**
     * @author SL
     * @param array $product
     * @return bool
     */
    public static function shouldDisplaySlider(array $product)
    {
        return (
            $product['deposit']['minimum_percentage'] != $product['deposit']['maximum_percentage'] &&
            $product['deposit']['minimum_amount'] != $product['deposit']['maximum_amount']
        );
    }

    /**
     * @author SL
     * @param array $product
     * @return mixed
     */
    public static function getProductId(array $product)
    {
        return $product['id'];
    }

    /**
     * @author SL
     * @param array $product
     * @return mixed
     */
    public static function getProductGroup(array $product)
    {
        return $product['product_group'];
    }

    /**
     * @author SL
     * @param array $product
     * @return float
     */
    public static function getDepositAmount(array $product)
    {
        return $product['credit_info']['deposit_amount']/100;
    }

    /**
     * @author SL
     * @param array $product
     * @return float
     */
    public static function getMinimumDeposit(array $product)
    {
        return $product['credit_info']['deposit_range']['minimum_amount']/100;
    }

    /**
     * @author SL
     * @param array $product
     * @return float
     */
    public static function getMaximumDeposit(array $product)
    {
        return $product['credit_info']['deposit_range']['maximum_amount']/100;
    }

    /**
     * @author SL
     * @param array $product
     * @return float
     */
    public static function getOrderAmount(array $product)
    {
        return $product['credit_info']['order_amount']/100;
    }
}