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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use WNowicki\Generic\Logger\PsrLoggerTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\RedirectException;
use Illuminate\Database\Eloquent\Builder;

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
        if (Request::capture()->get('limit') && is_int(Request::capture()->get('limit'))) {
            return Request::capture()->get('limit');
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
        return Request::capture()->except(['limit', 'page']);
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

        return redirect('locations')->with('success', ucwords($modelName) . ' was successfully deleted');
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
        } catch (\Exception $e) {

            throw (new RedirectException())->setTarget($redirect . '/' . $id . '/edit')->setError($e->getMessage());
        }
        return redirect()->back()->with('success', ucwords($modelName) .' details were successfully updated');
    }

    /**
     * @author WN
     * @param Builder $query
     */
    protected function processFilters(Builder $query)
    {
        $filter = $this->getTableFilter();
        if (count($filter) > 0) {
            foreach ($filter as $field => $value) {

                $query->where($field, 'like', '%' . $value . '%');
            }
        }
    }

    /**
     * @author WN
     * @param Builder $query
     * @return array
     */
    protected function prepareMessagesForIndexAction(Builder $query)
    {
        $messages = $this->getMessages();

        if (!$query->count()) {
            $messages['info'] = 'No records were found that matched your filter';
        }

        return $messages;
    }

    /**
     * @author WN
     * @param Builder $query
     * @param string $view
     * @param string $modelName
     * @return \Illuminate\View\View
     */
    protected function standardIndexAction(Builder $query, $view, $modelName)
    {
        $this->processFilters($query);

        return View(
            $view,
            [
                'messages' => $this->prepareMessagesForIndexAction($query),
                $modelName => $query->paginate($this->getPageLimit()),
            ]
        );
    }

    /**
     * @author WN
     * @param Builder $query
     */
    protected function limitToInstallationOnMerchant(Builder $query)
    {
        if (\Auth::user()->merchant_id) {
            $query->where(
                'installation_id',
                implode(', ', Installation::where('merchant_id', \Auth::user()->merchant_id)->get()->pluck('id')->all())
            );
        }
    }

    /**
     * @author WN
     * @param Builder $query
     * @param string $fieldName
     */
    protected function limitToMerchant(Builder $query, $fieldName = 'merchant_id')
    {
        if (\Auth::user()->merchant_id) {
            $query->where($fieldName, \Auth::user()->merchant_id);
        }
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
            $entity->merchant->id,
            $modelName,
            $redirect
        );
    }

    /**
     * @author WN
     * @param Model $model
     * @param $id
     * @param $modelName
     * @param $redirect
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
}
