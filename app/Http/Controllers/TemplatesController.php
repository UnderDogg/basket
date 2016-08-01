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
use App\Basket\Template;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class TemplatesController
 *
 * @author EB
 * @package App\Http\Controllers
 */
class TemplatesController extends Controller
{
    /**
     * @author EB
     * @param Installation $installation
     * @return \App\Basket\Template
     */
    public static function fetchDefaultTemplateForInstallation(Installation $installation)
    {
        try {
            return $installation->templates()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Template::first();
        }
    }
}
