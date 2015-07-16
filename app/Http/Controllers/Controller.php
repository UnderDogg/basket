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

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Request;

/**
 * Class Controller
 *
 * @author MS
 * @package App\Http\Controllers
 */
abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    // Default Pagination Record Limit
    const DEFAULT_PAGE_LIMIT = 15;



    /**
     * Make Message Object
     *
     * @author MS
     */
    protected function getMessages()
    {
        return [
            'success'   => session()->get('success'),
            'info'      => session()->get('info'),
            'error'     => session()->get('error'),
        ];
    }

    /**
     * Get Page Limit
     *
     * @author MS
     */
    protected function getPageLimit()
    {
        if (Request::get('limit') && is_int(Request::get('limit'))) {
            return Request::get('limit');
        }
        return self::DEFAULT_PAGE_LIMIT;
    }

    /**
     * Get Table Filter
     *
     * @author MS
     */
    protected function getTableFilter()
    {
        return Request::except('limit', 'page');
    }
}
