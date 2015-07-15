<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    const DEFAULT_PAGE_LIMIT = 25;

    protected $requestObject;

    protected $pageLimit;

    protected $tableFilter;

    protected $messages;

    public function __construct(Request $request)
    {
        $this->requestObject = $request;
        $this->setPageLimit();
        $this->setTableFilter();
        $this->makeMessageObject();
    }

    private function makeMessageObject()
    {
        $messages = new \stdClass();
        $messages->success = null;
        $messages->info = null;
        $messages->error = null;
        $this->messages = $messages;
    }

    private function setPageLimit()
    {
        $pageLimit = (int) $this->requestObject->only('limit')['limit'];
        $this->pageLimit = ($pageLimit) ? $pageLimit : self::DEFAULT_PAGE_LIMIT;
    }

    private function setTableFilter()
    {
        $filters = $this->requestObject->except('limit', 'page');
        $this->tableFilter = (!empty($filters)) ? $filters : null;
    }


}
