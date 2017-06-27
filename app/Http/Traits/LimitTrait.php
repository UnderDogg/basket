<?php

namespace App\Http\Traits;

use App\Basket\Installation;
use App\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait LimitTrait
{
    /**
     * Get Page Limit
     *
     * @author MS
     */
    protected function getPageLimit()
    {
        if (Request::capture()->get('limit') && is_numeric(Request::capture()->get('limit'))) {
            return Request::capture()->get('limit');
        }
        return (static::PAGE_LIMIT ? static::PAGE_LIMIT : 15);
    }

    /**
     * @author WN
     * @param Builder $query
     */
    protected function limitToInstallationOnMerchant(Builder $query)
    {
        if (\Auth::user()->merchant_id) {
            $query->whereIn(
                'installation_id',
                Installation::where('merchant_id', \Auth::user()->merchant_id)->get()->pluck('id')->all()
            );
        }
    }

    /**
     * @author WN
     * @param Builder $query
     * @param string $fieldName
     * @return Builder
     */
    protected function limitToMerchant(Builder $query, $fieldName = 'merchant_id')
    {
        if (\Auth::user()->merchant_id) {
            $query->where($fieldName, \Auth::user()->merchant_id);
        }
        return $query;
    }

    /**
     * @author WN
     * @param Builder $query
     * @return Builder
     */
    protected function limitToActive(Builder $query)
    {
        return $query->where('active', true);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function limitToOwnApplications(Builder $query)
    {
        if (!\Auth::user()->can(Permission::VIEW_ALL_APPLICATIONS)) {
            $query->where('user_id', \Auth::user()->id);
        }

        return $query;
    }
}
