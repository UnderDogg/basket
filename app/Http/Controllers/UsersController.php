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

use App\Exceptions\RedirectException;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        return view('user.create', ['messages' => $this->getMessages()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author MS
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $message = ['success','New User has been successfully created'];

        try {

            $array = $request->all();
            $array['merchant_id'] = $this->getAuthenticatedUser()->merchant_id;
            $array['password'] = bcrypt($array['password']);
            User::create($array);

        } catch (ModelNotFoundException $e) {

            $this->logError('Could not successfully create new User' . $e->getMessage());
            $message = ['error','Could not successfully create new User'];
        }

        return redirect('users')->with($message[0], $message[1]);
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
        return view('user.edit', ['user' => $this->fetchUserById($id), 'messages' => $this->getMessages()]);
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
            'password' => 'required',
        ]);

        $user = $this->fetchUserById($id);

        try {
            $input = $request->all();
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
        $user = $this->fetchModelById((new User()), $id, 'user', '/users');
        if (\Auth::user()->merchant_id == null || \Auth::user()->merchant_id == $user->merchant_id) {
            return $user;
        }
        throw RedirectException::make('/users')
            ->setError('You are not allowed to take any action on this Users');

    }
}
