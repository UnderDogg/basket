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
use App\Basket\Merchant;
use App\Basket\Location;
use App\Exceptions\RedirectException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    use DispatchesJobs, ValidatesRequests, PsrLoggerTrait;

    // Default Pagination Record Limit
    const DEFAULT_PAGE_LIMIT = 15;
    private $filters;

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
        if (Request::capture()->get('limit') && is_int(Request::capture()->get('limit'))) {
            return Request::capture()->get('limit');
        }
        return self::DEFAULT_PAGE_LIMIT;
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

            $this->filters = Collection::make(Request::capture()->except(['limit', 'page']));
        }

        return $this->filters;
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
     * @param $merchantId
     * @return bool
     */
    protected function isMerchantAllowedForUser($merchantId)
    {
        if (empty($this->getAuthenticatedUser()->merchant_id) ||
            $this->getAuthenticatedUser()->merchant_id == $merchantId
        ) {
            return true;
        }

        return false;
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
    protected function fetchModelById(Model $model, $id, $modelName, $redirect)
    {
        try {
            return $model->findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not get ' . $modelName . ' with ID [' . $id . ']; ' .
                ucwords($modelName) . ' does not exist: ' . $e->getMessage()
            );
            throw (new RedirectException())
                ->setTarget($redirect)
                ->setError('Could not found ' . ucwords($modelName) . ' with ID:' . $id);
        }
    }

    /**
     * @author WN
     * @param Model $model
     * @param int $id
     * @param string $modelName
     * @param string $redirect
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    protected function destroyModel(Model $model, $id, $modelName, $redirect)
    {
        try {
            $model->destroy($id);

        } catch (ModelNotFoundException $e) {

            $this->logError('Deletion of this record did not complete successfully' . $e->getMessage());
            throw (new RedirectException())
                ->setTarget($redirect)
                ->setError('Deletion of this record did not complete successfully');
        }

        return redirect($redirect)->with('messages', ['success', ucwords($modelName) . ' was successfully deleted']);
    }

    /**
     * @author WN
     * @param Model $model
     * @param int $id
     * @param string $modelName
     * @param string $redirect
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    protected function updateModel(Model $model, $id, $modelName, $redirect, Request $request)
    {
        $model = $this->fetchModelById($model, $id, $modelName, $redirect);
        try{
            $model->update($request->all());
            $active = $this->updateActiveField($id, $model, $request->active);
        } catch (\Exception $e) {

            throw (new RedirectException())->setTarget($redirect . '/' . $id . '/edit')->setError($e->getMessage());
        }
        return redirect()->back()->with('messages', ['success', ucwords($modelName) .' details were successfully updated']);
    }

    protected function updateActiveInstallation($id) {
        $model = new Installation();
        $model->neigh($id);
//        $model = new Installation();
//        $model->where('merchant_id', '=', $id);
//        $model->setActiveAttributeOnLocations($id,['active' => 0]);
//        $model->where('id','=',$id)->update(['active' => 0]);
    }

    //I believe we want installations id
    protected function updateLocationsActiveFromInstallations($id) {
        $model = new Installation();
        $model->where('id','=',$id);
        $model->setActiveAttributeOnLocations($id,['active' => 0]);

        //Sets installation to inactive, already done in request
        //$model->where('id','=',$id)->update(['active' => 0]);
    }

    protected function updateActiveField($id, $model, $active) {
        if($active == 0) {
            $model->activeFalse($id);
        } else {
            $model->activeTrue($id);
        }

    }

    /**
     * @author WN
     * @param Builder $query
     */
    protected function processFilters(Builder $query)
    {
        $filter = $this->getFilters();
        if (count($filter) > 0) {
            foreach ($filter as $field => $value) {

                $query->where($field, 'like', '%' . $value . '%');
            }
        }
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

        return view(
            $view,
            array_merge(
                [
                    $modelName => $query->paginate($this->getPageLimit()),
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
     * @author WN
     * @param Model $entity
     * @param int $merchantId
     * @param string $redirect
     * @param string $modelName
     * @return Model
     * @throws RedirectException
     */
    protected function checkModelForMerchantLimit(Model $entity, $merchantId, $modelName, $redirect)
    {
        if (!$this->isMerchantAllowedForUser($merchantId)) {
            throw RedirectException::make($redirect)
                ->setError('You are not allowed to take any action on this' . ucwords($modelName));
        }

        return $entity;
    }

    /**
     * @author EB
     * @return Carbon[]
     */
    protected function getDateRange()
    {
        $defaultDates = [
            'date_to' => Carbon::now(),
            'date_from' => new Carbon('last month')
        ];

        $filters = $this->getFilters();

        if($filters->has('date_to')) {
            $defaultDates['date_to'] = Carbon::createFromFormat('Y/m/d', $filters['date_to'])->hour(23)->minute(59)->second(59);
            $filters->forget('date_to');
        }

        if($filters->has('date_from')) {
            $defaultDates['date_from'] = Carbon::createFromFormat('Y/m/d', $filters['date_from']);
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
        return $model->where($field, '>', $after)->where($field, '<', $before);
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
     * @param $message
     * @return RedirectResponse
     */
    protected function redirectWithSuccessMessage($target, $message)
    {
        return redirect($target)
            ->with('messages', ['success' => $message]);
    }

    /**
     * @param $target
     * @param $message
     * @param \Exception $e
     * @return $this
     */
    protected function redirectWithException($target, $message, \Exception $e)
    {
        $this->logError($message .':' .$e->getMessage());
        return RedirectException::make($target)->setError($message);
    }
}
