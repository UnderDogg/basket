<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class FilterTrait
 *
 * @author EB
 * @package App\Http\Traits
 */
trait FilterTrait
{
    private $filters;

    /** @var array of money filters $moneyFilters */
    private $moneyFilters = [
        'ext_order_amount',
        'ext_finance_loan_amount',
        'ext_finance_deposit',
        'ext_finance_subsidy',
        'ext_finance_net_settlement'
    ];

    /**
     * @author WN
     * @param Builder $query
     */
    protected function processFilters(Builder $query)
    {
        $filter = $this->getFilters();
        if (count($filter) > 0) {
            foreach ($filter as $field => $value) {
                $value = $this->processMoneyFilters($field, $value);
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
    }

    /**
     * @author CS
     * @param string $field
     * @param string $value
     * @return string
     */
    protected function processMoneyFilters($field, $value)
    {
        if (in_array($field, $this->moneyFilters)) {
            return floor($value * 100);
        }

        return $value;
    }

    /**
     * Get Table Filter
     *
     * @author WN
     * @return Collection
     */
    protected function getFilters()
    {
        if (!$this->filters) {

            $this->filters = Collection::make(Request::capture()->except(['limit', 'page', 'download']));
        }

        return $this->filters;
    }

    /**
     * @author EB
     * @param $model
     * @param string $filter
     * @return array
     */
    protected function fetchFilterValues($model, $filter)
    {
        $rtn = [];
        if($model) {
            //Doing this because of Partial Refunds, needed as we are using a collection, not a builder
            ($model instanceof Builder) ? $model = $model->get() : $model = $model->all();
            foreach($model as $item) {
                $rtn[strtolower($item->{$filter})] = ucwords($item->{$filter});
            }
            $rtn = ['' => 'All'] + $rtn;
        }

        return $rtn;
    }

    /**
     * @author EB
     * @param Builder $model
     * @param string $filter
     * @param string $falseLabel
     * @param string $trueLabel
     * @return array
     */
    protected function fetchBooleanFilterValues($model, $filter, $falseLabel, $trueLabel)
    {
        $rtn = [];
        if($model) {
            foreach($model->get() as $item) {
                ($item->{$filter} == 0) ? $rtn[0] = ucwords($falseLabel) : $rtn[1] = ucwords($trueLabel);
            }
            $rtn = ['' => 'All'] + $rtn;
        }

        return $rtn;
    }

    /**
     * @author EB
     * @param Builder $model
     * @param string $associate
     * @return array
     */
    protected function fetchAssociateFilterValues($model, $associate)
    {
        $rtn = [];
        if($model) {
            foreach($model->get() as $item) {
                //Not null needed, because Users uses this, and a su can have null
                if($item->{$associate} !== null) {
                    $rtn[$item->{$associate}->id] = $item->{$associate}->name;
                }
            }
            $rtn = ['' => 'All'] + $rtn;
        }

        return $rtn;
    }
}
