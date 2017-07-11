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

use App\Basket\Merchant;
use App\Exceptions\Exception;
use App\Exceptions\RedirectException;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Role;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Class UsersController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @author MS
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::query();
        $this->limitToMerchant($users);
        return $this->standardIndexAction(
            $users,
            'user.index',
            'users',
            [
                'merchant_id' => $this->fetchAssociateFilterValues($users, 'merchant')
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author MS
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->renderFormPage('user.create', null, $this->fetchRoles(null));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author MS
     * @param UserStoreRequest $request
     * @return  \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function store(UserStoreRequest $request)
    {
        $array = $request->only(
            'name',
            'email',
            'password',
            'merchant_id'
        );

        if (!$this->isMerchantAllowedForUser($array['merchant_id'])) {
            throw RedirectException::make('/users')
                ->setError('You are not allowed to create User for this Merchant');
        }

        $array['password'] = bcrypt($array['password']);
        try {
            $user = User::create($array);
            $this->applyRoles(
                $user,
                array_values(
                    $request->except(
                        '_token',
                        'name',
                        'email',
                        'password',
                        'merchant_id',
                        'createUserButton'
                    )
                )
            );
        } catch (QueryException $e) {
            throw RedirectException::make('/users/create')
                ->setError('Cannot create User');
        }

        return $this->redirectWithSuccessMessage(
            '/users',
            'New user has been successfully created'
        );
    }

    /**
     * Display the specified resource.
     *
     * @author MS
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('user.show', ['user' => $this->fetchUserById($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @author MS
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        return $this->renderFormPage('user.edit', $id, $this->fetchRoles(User::findOrFail($id)));
    }

    /**
     * @author WN
     * @param $id
     * @return \Illuminate\View\View
     */
    public function editLocations($id)
    {
        return $this->renderFormPage('user.locations', $id, $this->fetchLocations(User::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param  int $id
     * @param UserUpdateRequest $request
     * @return  \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function update($id, UserUpdateRequest $request)
    {
        $user = $this->fetchUserById($id);

        $input = $request->all();

        try {
            if ($input['password']) {
                $user->password = bcrypt($input['password']);
            }
            unset($input['password']);

            if (!$user->update($input)) {
                throw new Exception('Problem saving object');
            }
            $this->applyRoles(
                $user,
                array_values(
                    $request->except(
                        '_method',
                        '_token',
                        'name',
                        'email',
                        'password',
                        'merchant_id',
                        'saveChanges'
                    )
                )
            );
        } catch (\Exception $e) {
            $this->logError('Cannot update user [' . $id . ']: ' . $e->getMessage());
            throw (new RedirectException())
                ->setTarget('/users/' . $id . '/edit')
                ->setError('Cannot update user [' . $id . ']');
        }

        return $this->redirectWithSuccessMessage(
            '/users',
            'User details were successfully updated'
        );
    }

    /**
     * @author WN
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function updateLocations($id, Request $request)
    {
        try {
            $user = $this->fetchUserById($id);
        } catch (\Exception $e) {
            $this->redirectWithException('/users/' . $id . '/edit', 'Cannot fetch user ' . $id, $e);
        }

        return $this->validateLocations($id, $user, $request->except('_method', '_token', 'saveChanges'));
    }

    /**
     * * The function validates the user location update request by matching the locations ids that are assigned to the
     * user's installation to the locations ids sent by the request. If the number of matches are not equal to the
     * number of locations ids in the request a invalid location id is present in the request and an exception is
     * thrown. If the validation is passed the locations are updated.
     *
     * @author EA
     * @param int $id
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    private function validateLocations($id, $user, $request)
    {
        $locations = $this->fetchMerchantLocationsFromUser($user);
        if (count(array_intersect($locations->pluck('id')->toArray(), $request)) != count($request)) {
            $this->logError('Cannot update user [' . $id . '] locations: Locations for the user are invalid ');
            return redirect('/users/' . $id . '/locations')
                ->with('messages', ['error' => 'Locations for the user are invalid']);
        } else {
            try {
                $user->locations()->sync(array_values($request));
                return $this->redirectWithSuccessMessage(
                    '/users',
                    'User details were successfully updated'
                );
            } catch (\Exception $e) {
                $this->logError('Cannot update user [' . $id . '] locations: ' . $e->getMessage());
                return RedirectException::make('users' . $id . '/edit')->setError($e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author WN
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function destroy($id)
    {
        if ($id == $this->getAuthenticatedUser()->id) {
            throw RedirectException::make('/')->setError('You cannot delete yourself!');
        }

        try {
            return $this->destroyModel((new User()), $id, 'user', '/users');
        } catch (\Exception $e) {
            Log::error('Problem deleting user [' .  $id. ']: ' . $e->getMessage());
            throw RedirectException::make(
                '/users/' . $id
            )->setError('There was a problem deleting the selected user. If this error persists,' .
                ' please contact afforditNOW! Support.');
        }
    }

    /**
     * Delete
     *
     * @author MS
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function delete($id)
    {
        $user = $this->fetchUserById($id);
        $user->type = 'users';
        $user->controller = 'Users';
        return view('includes.page.confirm_delete', ['object' => $user]);
    }

    /**
     * @author WN
     * @param $id
     * @return User
     * @throws \App\Exceptions\RedirectException
     */
    private function fetchUserById($id)
    {
        return $this->fetchModelByIdWithMerchantLimit((new User()), $id, 'user', '/users');
    }

    /**
     * @author WN
     * @param string $view
     * @param int|null $userId
     * @param array $additionalProperties
     * @return \Illuminate\View\View
     */
    private function renderFormPage($view, $userId = null, array $additionalProperties = [])
    {
        $user = ($userId !== null ? $this->fetchUserById($userId) : null);

        $merchants = Merchant::query();
        $this->limitToMerchant($merchants, 'id');

        return view(
            $view,
            array_merge(
                [
                    'user' => $user,
                    'merchants' => $merchants->get()->pluck('name', 'id')->toArray(),
                ],
                $additionalProperties
            )
        );
    }

    /**
     * @author EB
     * @param User $user
     * @return array
     * @throws RedirectException
     */
    private function fetchLocations($user)
    {
        $locations = $this->fetchMerchantLocationsFromUser($user);
        $locationsApplied = $user->locations;

        return [
            'locationsApplied' => $locationsApplied,
            'locationsAvailable' =>
                $locations->diff($locationsApplied)->keyBy('id'),
        ];
    }

    /**
     * @author EB
     * @param User $user
     * @return array
     */
    private function fetchRoles($user)
    {
        $roles = $this->fetchAvailableRoles();
        if ($user !== null) {
            $rolesApplied = $user->roles;
            $rolesAvailable = $roles->diff($rolesApplied)->keyBy('id');
        } else {
            $rolesApplied = collect([]);
            $rolesAvailable = $roles->keyBy('id');
        }

        return [
            'rolesApplied' => $rolesApplied,
            'rolesAvailable' => $rolesAvailable,
        ];
    }

    /**
     * @author WN, EB
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function fetchAvailableRoles()
    {
        $roles = Role::all()->keyBy('id');

        if (array_search(
            RolesController::SUPER_USER_NAME,
            $this->getAuthenticatedUser()->roles->pluck('name')->toArray()
        ) === false) {
            $roles->forget($this->fetchSingleSuRoleByName(RolesController::SUPER_USER_NAME)->id);
            $roles->forget($this->fetchSingleSuRoleByName(RolesController::READ_ONLY_NAME)->id);
        }

        return $roles;
    }

    /**
     * @author WN
     * @param User $user
     * @param $roles
     * @throws Exception
     */
    private function applyRoles(User $user, array $roles)
    {
        if (count($ar = $this->filterSuRole($roles)) > 0) {
            $user->merchant_id = null;
            if (!$user->save()) {
                throw new Exception('Cannot remove Merchant form Super User');
            }
            $roles = $ar;
        }

        $user->roles()->sync($roles);
    }

    /**
     * Filters SU roles to return a single SU role
     *
     * @author EB, WN
     * @param array $roles
     * @return array
     */
    private function filterSuRole(array $roles)
    {
        if (count(array_intersect($roles, $this->fetchSuRoles()->pluck('id')->toArray())) > 0) {
            if (in_array($id = $this->fetchSingleSuRoleByName(RolesController::SUPER_USER_NAME)->id, $roles)) {
                return [$id];
            }

            return [$this->fetchSingleSuRoleByName(RolesController::READ_ONLY_NAME)->id];
        }

        return [];
    }

    /**
     * @author EB
     * @return \Illuminate\Support\Collection
     */
    private function fetchSuRoles()
    {
        return Collection::make([
            $this->fetchRoleByName(RolesController::SUPER_USER_NAME),
            $this->fetchRoleByName(RolesController::READ_ONLY_NAME),
        ]);
    }

    /**
     * @author EB
     * @param string $name
     * @return Role
     */
    private function fetchSingleSuRoleByName($name)
    {
        return $this->fetchSuRoles()->whereStrict('name', $name)->first();
    }
}
