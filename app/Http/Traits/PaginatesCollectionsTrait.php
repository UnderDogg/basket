<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

/**
 * Trait PaginatesCollectionsTrait
 * @author SL
 * @package App\Http\Traits
 */
trait PaginatesCollectionsTrait
{
    /**
     * @author SL
     * @param Collection $collection
     * @param Request $request
     * @param int $recordsPerPage
     * @return LengthAwarePaginator
     */
    private function convertCollectionToPaginator(Collection $collection, Request $request, $recordsPerPage = null)
    {
        $recordsPerPage = (is_null($recordsPerPage) ? env('VIEW_PAGINATION_COUNT', 15) : $recordsPerPage);

        $paginator = new LengthAwarePaginator(
            $collection->forPage(Input::get('page', 1), $recordsPerPage),
            $collection->count(),
            $recordsPerPage
        );

        $paginator->withPath('/' . $request->path());

        return $paginator;
    }
}