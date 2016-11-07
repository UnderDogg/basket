<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Synchronisation;

use App\Basket\Installation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PayBreak\Sdk\Entities\Application\ApplicantEntity;
use PayBreak\Sdk\Entities\Application\OrderEntity;
use PayBreak\Sdk\Entities\Application\ProductsEntity;

/**
 * Initialise Application Helper
 *
 * @author EB
 * @package App\Basket\Synchronisation
 */
class InitialiseApplicationHelper
{
    /**
     * @author EB
     * @param Request $request
     * @param Installation $installation
     * @return OrderEntity
     */
    public static function createOrderEntity(Request $request, Installation $installation)
    {
        return OrderEntity::make([
            'reference' => $request->get('reference'),
            'amount' => (int) $request->get('amount'),
            'description' => $request->get('description'),
            'validity' => Carbon::now()->addSeconds($installation->validity)->toDateTimeString(),
            'deposit_amount' => $request->has('deposit') ? ($request->get('deposit') * 100) : $request->get('deposit'),
        ]);
    }

    /**
     * @author EB
     * @param Request $request
     * @return ProductsEntity
     */
    public static function createProductsEntity(Request $request)
    {
        return ProductsEntity::make([
            'group' => $request->get('group'),
            'options' => [$request->get('product')],
        ]);
    }

    /**
     * @author EB
     * @param Request $request
     * @return ApplicantEntity
     */
    public static function createApplicantEntity(Request $request)
    {
        return ApplicantEntity::make([
            'title' => $request->get('title'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email_address' =>
                $request->has('applicant_email') ?
                    $request->get('applicant_email') :
                    $request->get('email'),
            'phone_home' => $request->get('phone_home'),
            'phone_mobile' => $request->get('phone_mobile'),
            'postcode' => $request->get('postcode'),
        ]);
    }
}
