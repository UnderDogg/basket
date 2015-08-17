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

use App\Basket\Location;
use App\Basket\Merchant;
use App\Exceptions\Exception;
use App\Exceptions\RedirectException;
use App\Http\Requests;
use App\Role;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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
        return $this->standardIndexAction($users, 'user.index', 'user');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author MS
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->renderFormPage('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author MS
     * @param Request $request
     * @return  \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'merchant_id' => 'required',
        ]);

        $array = $request->all();

        if (!$this->isMerchantAllowedForUser($array['merchant_id'])) {
            throw RedirectException::make('/users')
                ->setError('You are not allowed to create User for this Merchant');
        }

        $array['password'] = bcrypt($array['password']);

        try {
            $user = User::create($array);
            $this->processRoles($user, $array);

        } catch (QueryException $e) {
            throw RedirectException::make('/users/create')
                ->setError('Cannot create User');
        }
        return redirect('users')->with('messages', ['success' => 'New user has been successfully created']);
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
        return $this->renderFormPage('user.edit', $id);
    }

    /**
     * @author WN
     * @param $id
     * @return \Illuminate\View\View
     */
    public function editLocations($id)
    {
        return $this->renderFormPage('user.locations', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param  int $id
     * @param Request $request
     * @return  \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'merchant_id' => 'required',
        ]);

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

            $this->processRoles($user, $input);

        } catch (\Exception $e) {
            $this->logError('Cannot update user [' . $id . ']: ' . $e->getMessage());
            throw (new RedirectException())->setTarget('/users/' . $id . '/edit')->setError($e->getMessage());
        }


        return redirect()->back()->with('messages', ['success' => 'User details were successfully updated']);
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

            $ids = explode(':', $request->get('locationsApplied'));
            array_shift($ids);
            $user->locations()->sync($ids);

        } catch (\Exception $e) {
            $this->logError('Cannot update user [' . $id . '] locations: ' . $e->getMessage());
            throw (new RedirectException())->setTarget('/users/' . $id . '/edit')->setError($e->getMessage());
        }

        return redirect()->back()->with('messages', ['success' => 'User details were successfully updated']);
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

        return $this->destroyModel((new User()), $id, 'user', '/users');
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
     * @return \Illuminate\View\View
     */
    private function renderFormPage($view, $userId = null)
    {
        $user = ($userId !== null ? $this->fetchUserById($userId) : null);

        $locations = Location::all();

        if ($user !== null) {
            $locationsApplied = $user->locations;
            $locationsAvailable = $locations->diff($locationsApplied)->keyBy('id');
        } else {
            $locationsApplied = collect([]);
            $locationsAvailable = $locations->keyBy('id');
        }

        $roles = $this->fetchAvailableRoles();
        if ($user !== null) {
            $rolesApplied = $user->roles;
            $rolesAvailable = $roles->diff($rolesApplied)->keyBy('id');
        } else {
            $rolesApplied = collect([]);
            $rolesAvailable = $roles->keyBy('id');
        }

        $merchants = Merchant::query();
        $this->limitToMerchant($merchants, 'id');

        return view(
            $view,
            [
                'user' => $user,
                'merchants' => $merchants->get()->pluck('name', 'id')->toArray(),
                'locationsApplied' => $locationsApplied,
                'locationsAvailable' => $locationsAvailable,
                'rolesApplied' => $rolesApplied,
                'rolesAvailable' => $rolesAvailable,
            ]
        );
    }

    /**
     * @author WN
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function fetchAvailableRoles()
    {
        $roles = Role::all();

        if (array_search(1, $this->getAuthenticatedUser()->roles->pluck('id')->toArray()) === false) {

            $roles->forget(0);
        }

        return $roles;
    }

    /**
     * @author WN
     * @param User $user
     * @param array $input
     * @throws Exception
     */
    private function processRoles(User $user, array $input)
    {
        if (isset($input['rolesApplied'])) {
            $ids = explode(':', $input['rolesApplied']);
            array_shift($ids);

            $this->applyRoles($user, $ids);
        }
    }


    /**
     * @author WN
     * @param User $user
     * @param $roles
     * @throws Exception
     */
    private function applyRoles(User $user, array $roles)
    {
        if (array_search('1', $roles) !== false) {

            $user->merchant_id = null;
            if (!$user->save()) {
                throw new Exception('Cannot remove Merchant form Super User');
            }
            $roles = [1];
        }

        $user->roles()->sync($roles);
    }
}
