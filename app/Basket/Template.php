<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket;

use Illuminate\Database\Eloquent\Model;

/**
 * Template Model
 *
 * @author EB
 * @property int    $id
 * @property int    $merchant_id
 * @property string $html
 * @property string $name
 * @property        $created_at
 * @property        $updated_at
 * @property Merchant $merchant
 * @property Installation[] $installations
 * @package App\Basket
 */
class Template extends Model
{
