<?php

namespace App\Http\Traits;

use App\Basket\Installation;
use App\Exceptions\RedirectException;
use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class ModelTrait
 *
 * @author EB
 * @package App\Http\Traits
 */
trait ModelTrait
{
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
            $model->findOrFail($id);
            $model->destroy($id);
        } catch (ModelNotFoundException $e) {
            $this->logError('Deletion of this record did not complete successfully' . $e->getMessage());
            throw (new RedirectException())
                ->setTarget($redirect)
                ->setError('Deletion of this record did not complete successfully');
        }

        return redirect($redirect)->with('messages', ['success' => ucwords($modelName) . ' was successfully deleted']);
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
        try {
            $this->updateActiveField($model, $request->has('active'));
            $model->update($request->all());
        } catch (\Exception $e) {
            throw (new RedirectException())->setTarget($redirect . '/' . $id . '/edit')->setError($e->getMessage());
        }

        return redirect()->to($redirect . '/' . $id . '/edit' . $this->shouldReturnToTab($request))
            ->with('messages', ['success' => ucwords($modelName) .' details were successfully updated']);
    }

    /**
     * Returns to a specific tab on a page, if specified
     *
     * @author EB
     * @param Request $request
     * @return string
     */
    private function shouldReturnToTab(Request $request)
    {
        return $request->has('save') ? '#' . $request->get('save') : '';
    }

    /**
     * @author EB, WN
     * @param Model $model
     * @param bool $active
     * @return Model
     */
    protected function updateActiveField($model, $active)
    {
        if ($model->active ^ $active) {
            if ($active) {
                if (method_exists($model, 'activate')) {
                    $model->activate();
                }
            } else {
                if (method_exists($model, 'deactivate')) {
                    $model->deactivate();
                }
            }
        }

        return $model;
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
                ->setError('You are not allowed to take any action on this ' . ucwords($modelName));
        }

        return $entity;
    }

    /**
     * @author WN
     * @param int $merchantId
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
     * @param int $id
     * @return Installation
     * @throws RedirectException
     */
    protected function fetchInstallation($id)
    {
        return $this->fetchModelByIdWithMerchantLimit((new Installation()), $id, 'installation', '/installations');
    }

    /**
     * @author WN, EB
     * @param int $id
     * @return Role
     * @throws \App\Exceptions\RedirectException
     */
    protected function fetchRoleById($id)
    {
        return $this->fetchModelById((new Role()), $id, 'role', '/roles');
    }

    /**
     * @author EB
     * @param $name
     * @return Model
     * @throws RedirectException
     */
    protected function fetchRoleByName($name)
    {
        return $this->fetchModelByField((new Role()), $name, 'role', '/roles', 'name');
    }

    /**
     * @author EB
     * @param Model $model
     * @param string $id
     * @param string $modelName
     * @param string $redirect
     * @param string $field
     * @return Model
     * @throws RedirectException
     */
    protected function fetchModelByField(Model $model, $id, $modelName, $redirect, $field = 'name')
    {
        try {
            return $model->where($field, '=', $id)->first();
        } catch (ModelNotFoundException $e) {
            $this->logError(
                'Could not get ' . $modelName . ' with ' . $field . ' [' . $id . ']; ' .
                ucwords($modelName) . ' does not exist: ' . $e->getMessage()
            );
            throw (new RedirectException())
                ->setTarget($redirect)
                ->setError('Could not find ' . ucwords($modelName) . ' with ' . $field . ':' . $id);
        }
    }

    /**
     * @author WN
     * @param string $message
     * @param array $context
     * @return null
     */
    abstract protected function logError($message, array $context = []);

    /**
     * @author WN
     * @return \App\User|null
     */
    abstract protected function getAuthenticatedUser();
}
