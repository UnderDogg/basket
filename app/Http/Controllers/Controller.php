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
use App\Basket\Location;
use App\Basket\Merchant;
use App\Exceptions\RedirectException;
use App\Http\Traits\FilterTrait;
use App\Http\Traits\LimitTrait;
use App\Http\Traits\ModelTrait;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
    use DispatchesJobs, ValidatesRequests, PsrLoggerTrait, ModelTrait, FilterTrait, LimitTrait;

    // Default Pagination Record Limit
    const PAGE_LIMIT = 15;

    // Types of filters
    const FILTER_STRICT = 1;
    const FILTER_FINANCE = 2;
    const FILTER_NULL = 4;

    const FILTER_IS_NULL_KEY = 'null';
    const FILTER_IS_NOT_NULL_KEY = 'not_null';

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
     * @return \App\User
     * @throws ModelNotFoundException
     */
    protected function getAuthenticatedUser()
    {
        if (\Auth::getUser()) {
            return \Auth::getUser();
        }

        throw new ModelNotFoundException('Authenticated user not found');
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
     * @param string $field
     * @return Application
     */
    protected function fetchApplicationDetails($id, $field = 'ext_id')
    {
        return Application::where($field, '=', $id)->first();
    }

    /**
     * @author SL, EB
     * @param \Exception $e
     * @param int $defaultCode
     * @return Response
     */
    protected function apiResponseFromException(\Exception $e, $defaultCode = 500)
    {
        $code = $e->getCode();

        if (is_null($code) || $code === 0) {
            $code = $defaultCode;
        }

        return (new Response(['error' => $e->getMessage()], $code))->header('Content-Type', 'json');
    }

    /**
     * @author EB, EA, WN
     * @param Merchant $merchant
     * @return Collection
     */
    protected function fetchMerchantLocations(Merchant $merchant)
    {
        $installations = $merchant->installations()->get();

        $merchantLocations = new Collection();

        foreach ($installations as $installation) {
            $installationLocations = $installation->locations()->get();
            foreach ($installationLocations as $location) {
                $merchantLocations->push($location);
            }
        }

        return $merchantLocations;
    }

    /**
     * Returns all locations from a merchant. If merchant for the user is null (SU), redirects with error
     *
     * @author EA, EB
     * @param User $user
     * @return Collection
     * @throws RedirectException
     */
    protected function fetchMerchantLocationsFromUser(User $user)
    {
        if ($user->merchant_id == null) {
            throw RedirectException::make('/users')
                ->setError('Super Users do not belong to a Merchant, cannot fetch Locations');
        }

        try {
            return $this->fetchMerchantLocations(Merchant::findOrFail($user->merchant_id));
        } catch (ModelNotFoundException $e) {
            throw RedirectException::make('users/' . $user->id)->setError($e->getMessage());
        }
    }

    /**
     * @author WN
     * @param $id
     * @return Location
     * @throws RedirectException
     */
    protected function fetchLocation($id)
    {
        $location = $this->fetchModelByIdWithInstallationLimit((new Location()), $id, 'location', '/locations');

        if (!in_array($id, $this->getAuthenticatedUser()->locations->pluck('id')->all())) {
            throw RedirectException::make('/')->setError('You don\'t have permission to access this Location');
        }

        return $location;
    }

    /**
     * Returns blank configuration for filters
     *
     * @author EB
     * @return array
     */
    protected function getFiltersConfiguration()
    {
        return [];
    }

    /**
     * @author EB
     * @param Installation $installation
     * @return array
     */
    protected function fetchInstallationProductLimits(Installation $installation)
    {
        return $installation->productLimits->keyBy('product')->toArray();
    }
}
