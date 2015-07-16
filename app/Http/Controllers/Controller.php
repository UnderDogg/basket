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
use Illuminate\Http\Request;

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
    const DEFAULT_PAGE_LIMIT = 10;

    /** @var Request $requestObject */
    protected $requestObject;

    /** @var  int $pageLimit */
    protected $pageLimit;

    /** @var  array $tableFilter */
    protected $tableFilter;

    /** @var  Object $messages */
    protected $messages;

    /**
     * @author MS
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->requestObject = $request;
        $this->setPageLimit();
        $this->setTableFilter();
        $this->makeMessageObject();
    }

    /**
     * Make Message Object
     *
     * @author MS
     */
    private function makeMessageObject()
    {
        $messages = new \stdClass();
        $messages->success = null;
        $messages->info = null;
        $messages->error = null;

        $this->messages = $messages;

        if (session()->get('message')) {
            $this->messages->success[] = session()->get('message');
        }
    }

    /**
     * Set Page Limit
     *
     * @author MS
     */
    private function setPageLimit()
    {
        $pageLimit = (int) $this->requestObject->only('limit')['limit'];
        $this->pageLimit = ($pageLimit) ? $pageLimit : self::DEFAULT_PAGE_LIMIT;
    }

    /**
     * Set Table Filter
     *
     * @author MS
     */
    private function setTableFilter()
    {
        $filters = $this->requestObject->except('limit', 'page');
        $this->tableFilter = (!empty($filters)) ? $filters : null;
    }
}
