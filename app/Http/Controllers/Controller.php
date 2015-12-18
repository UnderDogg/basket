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

use App\Basket\Application;
use App\Basket\Installation;
use App\Basket\Merchant;
use App\Exceptions\RedirectException;
use App\Http\Traits\FilterTrait;
use App\Http\Traits\ModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Class Controller
 *
 * @author MS
 * @package App\Http\Controllers
 */
abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, PsrLoggerTrait, ModelTrait, FilterTrait;

    // Default Pagination Record Limit
    const DEFAULT_PAGE_LIMIT = 15;

    /**
     * Get Merchant Token
     *
     * @author MS
     * @return string
     * @throws RedirectException
     */
    protected function getMerchantToken()
    {
        $merchant = $this
            ->fetchModelById((new Merchant()), $this->getAuthenticatedUser()->merchant_id, 'merchant', '/');
        return $merchant->token;
    }

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
        return self::DEFAULT_PAGE_LIMIT;
    }

    /**
     * Get Logger
     *
     * @author MS
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return \Log::getMonolog();
    }

    /**
     * @author WN
     * @return \App\User|null
     */
    protected function getAuthenticatedUser()
    {
        return \Auth::getUser();
    }

    /**
     * @author WN
     * @param Builder $query
     * @param string $view
     * @param string $modelName
     * @param array $additionalProperties View properties
     * @return \Illuminate\View\View
     */
    protected function standardIndexAction(
        Builder $query,
        $view,
        $modelName,
        array $additionalProperties = []
    ) {
        $this->processFilters($query);
        $data = $query->paginate($this->getPageLimit());

        return view(
            $view,
            array_merge(
                [
                    $modelName => $data,
                    'api_data' => $data->items(),
                ],
                $additionalProperties
            )
        );
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
     * @author WN
     * @param Model $model
     * @param int $id
     * @param string $modelName
     * @param string $redirect
     * @return Model
     * @throws RedirectException
     */
    protected function fetchModelByIdWithMerchantLimit(Model $model, $id, $modelName, $redirect)
    {
        return $this->checkModelForMerchantLimit(
            ($entity = $this->fetchModelById($model, $id, $modelName, $redirect)),
            $entity->merchant?$entity->merchant->id:null,
            $modelName,
            $redirect
        );
    }

    /**
     * @author WN
     * @param Model $model
     * @param int $id
     * @param string $modelName
     * @param string $redirect
     * @return Model
     * @throws RedirectException
     */
    protected function fetchModelByIdWithInstallationLimit(Model $model, $id, $modelName, $redirect)
    {
        return $this->checkModelForMerchantLimit(
            ($entity = $this->fetchModelById($model, $id, $modelName, $redirect)),
            $entity->installation->merchant->id,
            $modelName,
            $redirect
        );
    }

    /**
     * @author EB
     * @return Carbon[]
     */
    protected function getDateRange()
    {
        $defaultDates = [
            'date_to' => Carbon::now(),
            'date_from' => Carbon::today(),
        ];

        $filters = $this->getFilters();

        if($filters->has('date_to')) {
            $defaultDates['date_to'] = Carbon::createFromFormat('Y/m/d', $filters['date_to'])->endOfDay();
            $filters->forget('date_to');
        }

        if($filters->has('date_from')) {
            $defaultDates['date_from'] = Carbon::createFromFormat('Y/m/d', $filters['date_from'])->startOfDay();
            $filters->forget('date_from');
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

    /**
     * @author WN
     * @param int $id
     * @return Merchant
     * @throws RedirectException
     */
    protected function fetchMerchantById($id)
    {
        return $this->checkModelForMerchantLimit(
            $this->fetchModelById((new Merchant()), $id, 'merchant', '/merchants'),
            $id,
            'merchant',
            '/merchants'
        );
    }

    /**
     * @param $target
     * @param string $message
     * @return RedirectResponse
     */
    protected function redirectWithSuccessMessage($target, $message)
    {
        return redirect($target)
            ->with('messages', ['success' => $message]);
    }

    /**
     * @param $target
     * @param string $message
     * @param \Exception $e
     * @return RedirectException
     */
    protected function redirectWithException($target, $message, \Exception $e)
    {
        $this->logError($message .':' .$e->getMessage());
        return RedirectException::make($target)->setError($message);
    }

    /**
     * @author EB
     * @param $id
     * @return mixed
     */
    protected function fetchApplicationDetails($id)
    {
        return Application::where('ext_id', '=', $id)->first();
    }
}
