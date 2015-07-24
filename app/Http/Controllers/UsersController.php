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
        $messages = $this->getMessages();
        $user = null;

        try {

            $user = User::query();

            if (!empty($filter = $this->getTableFilter())) {
                foreach ($filter as $field => $query) {

                    $user->where($field, 'like', '%' . $query . '%');
                }
                if (!$user->count()) {
                    $messages['info'] = 'No records were found that matched your filter';
                }
            }

            $user = $user->paginate($this->getPageLimit());

        } catch (ModelNotFoundException $e) {

            $this->logError('Error occurred getting locations: ' . $e->getMessage());
            $messages['error'] = 'Error occurred getting locations';

        }

        return View('user.index', ['user' => $user, 'messages' => $messages]);
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
     * @author MS
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function destroy($id)
    {
        $message = ['success','User was successfully deleted'];
        try {

            User::destroy($id);

        } catch (ModelNotFoundException $e) {

            $this->logError('Deletion of this record did not complete successfully' . $e->getMessage());
            $message = ['error', 'Deletion of this record did not complete successfully'];
        }

        return redirect('users')->with($message[0], $message[1]);
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
        return $this->fetchModelById((new User()), $id, 'user', '/users');
    }
}
