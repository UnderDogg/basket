<?php

namespace App\Http\Traits;

use App\Exceptions\RedirectException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use WNowicki\Generic\Exception;

/**
 * Class FilterTrait
 *
 * @author EB
 * @package App\Http\Traits
 */
trait FilterTrait
{
    private $filters;

    /**
     * @author EB
     * @return array
     */
    abstract protected function getFiltersConfiguration();

    /**
     * @author WN, EB
     * @param Builder $query
     */
    protected function processFilters(Builder $query)
    {
        $filter = $this->getFilters();
        $config = $this->getFiltersConfiguration();
        if (count($filter) > 0) {
            foreach ($filter as $field => $value) {
                $this->processFilterTypes($config, $field, $value, $query);
            }
        }
    }

    /**
     * @author EB
     * @param array $config
     * @param string $field
     * @param mixed $value
     * @param Builder $query
     * @throws Exception
     */
    protected function processFilterTypes(array $config, $field, $value, Builder $query)
    {
        if (array_key_exists($field, $config)) {
            switch ($config[$field]) {
                case Controller::FILTER_STRICT:
                    $query->where($field, '=', $value);
                    break;
                case Controller::FILTER_FINANCE:
                    $query->where(
                        $field,
                        'like',
                        '%' . $this->processMoneyFilters($value) . '%'
                    );
                    break;
                default:
                    throw new Exception('Unhandled filter for field ' . $field);
            }
        } else {
            $query->where($field, 'like', '%' . $value . '%');
        }
    }

    /**
     * @author CS, EB
     * @param string $value
     * @return int
     */
    protected function processMoneyFilters($value)
    {
        return (int) floor($value * 100);
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
        if ($model) {
            //Doing this because of Partial Refunds, needed as we are using a collection, not a builder
            ($model instanceof Builder) ? $model = $model->get() : $model = $model->all();
            foreach ($model as $item) {
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
        if ($model) {
            foreach ($model->get() as $item) {
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
        if ($model) {
            foreach ($model->get() as $item) {
                //Not null needed, because Users uses this, and a su can have null
                if ($item->{$associate} !== null) {
                    $rtn[$item->{$associate}->id] = $item->{$associate}->name;
                }
            }
            $rtn = ['' => 'All'] + $rtn;
        }

        return $rtn;
    }

    /**
     * @author EB
     * @return Carbon[]
     * @throws \App\Exceptions\RedirectException
     */
    protected function getDateRange()
    {
        $defaultDates = [
            'date_to' => Carbon::now(),
            'date_from' => Carbon::today(),
        ];

        $filters = $this->getFilters();

        try {
            if ($filters->has('date_to')) {
                $defaultDates['date_to'] = Carbon::createFromFormat('Y/m/d', $filters['date_to'])->endOfDay();
                $filters->forget('date_to');
            }

            if ($filters->has('date_from')) {
                $defaultDates['date_from'] = Carbon::createFromFormat('Y/m/d', $filters['date_from'])->startOfDay();
                $filters->forget('date_from');
            }
        } catch (\InvalidArgumentException $e) {
            throw RedirectException::make($this->getRedirectUrl())->setError('Invalid filter date(s)');
        }

        return $defaultDates;
    }

    /**
     * @param Builder $model
     * @param string $field
     * @param Carbon $after
     * @param Carbon $before
     * @return Builder
     */
    protected function processDateFilters(Builder $model, $field, Carbon $after, Carbon $before)
    {
        return $model->where($field, '>', $after->toDateTimeString())->where($field, '<', $before->toDateTimeString());
    }
}
