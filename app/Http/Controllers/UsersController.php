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
use App\Exceptions\RedirectException;
use App\Http\Requests;
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
     * @return \Illuminate\View\View
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

            $input = $request->all();
            if (isset($input['locationsApplied'])) {
                $ids = explode(':', $input['locationsApplied']);
                array_shift($ids);
                $user->locations()->sync($ids);
            }

        } catch (QueryException $e) {
            throw RedirectException::make('/users/create')
                ->setError('Can\'t create User');
        }

        return redirect('users')->with('success', 'New User has been successfully created');
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
        return view('user.show', ['user' => $this->fetchUserById($id), 'messages' => $this->getMessages()]);
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
     * Update the specified resource in storage.
     *
     * @author MS
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\View\View
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

        if (!$this->isMerchantAllowedForUser($input['merchant_id'])) {

            throw RedirectException::make('/users')
                ->setError('You are not allowed to create User for this Merchant');
        }

        try {

            if (isset($input['locationsApplied'])) {
                $ids = explode(':', $input['locationsApplied']);
                array_shift($ids);
                $user->locations()->sync($ids);
            }

            $input['password'] = bcrypt($input['password']);
            $user->update($input);
        } catch (\Exception $e) {
            $this->logError('Can not update user [' . $id . ']: ' . $e->getMessage());
            throw (new RedirectException())->setTarget('/users/' . $id . '/edit')->setError($e->getMessage());
        }


        return redirect()->back()->with('success', 'User details were successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author WN
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function destroy($id)
    {
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
        return view('includes.page.confirm_delete', ['object' => $user, 'messages' => $this->getMessages()]);
    }

    /**
     * @author WN
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\RedirectException
     */
    private function fetchUserById($id)
    {
        return $this->fetchModelByIdWithMerchantLimit((new User()), $id, 'user', '/users');
    }

    private function renderFormPage($view, $userId = null)
    {
        $allLocations = Location::all();
        $user = ($userId !== null ? $this->fetchUserById($userId) : null);

        if ($user !== null) {

            $locationsApplied = $user->locations;
            $locationsAvailable = $allLocations->keyBy('id');

            foreach ($locationsApplied as $location) {
                $locationsAvailable->pull($location->id);
            }

            $locationsAvailable->all();

        } else {
            $locationsApplied = collect([]);
            $locationsAvailable = $allLocations;
        }

        $merchants = Merchant::query();
        $this->limitToMerchant($merchants, 'id');

        return view(
            $view,
            [
                'user' => $user,
                'messages' => $this->getMessages(),
                'merchants' => $merchants->get()->pluck('name', 'id')->toArray(),
                'locationsApplied' => $locationsApplied,
                'locationsAvailable' => $locationsAvailable,
            ]
        );
    }
}
