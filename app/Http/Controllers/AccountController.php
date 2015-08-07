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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

/**
 * Class AccountController
 *
 * @author EB
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    /**
     * @author EB
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return $this->renderView('show');
    }

    /**
     * @author EB
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return $this->renderView('edit');
    }

    /**
     * @author WN
     * @param $view
     * @return \Illuminate\View\View
     */
    private function renderView($view)
    {
        return view('account.' . $view,
            [
                'messages' => $this->getMessages(),
                'user' => $this->getAuthenticatedUser(),
            ]
        );
    }

    /**
     * @author EB
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);
        $user->update($request->all());
        return Redirect::back()->with(['success' => 'Your details have successfully been changed']);
    }


    /**
     * @author EB
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|different:old_password',
            'new_password_confirmation' => 'required|different:old_password|same:new_password',
        ]);

        if(!Hash::check($request->get("old_password"), $user->getAuthPassword())) {
            return Redirect::back()->withInput()->with(['error' => 'The password entered does not match our records']);
        }

        $user->password = Hash::make($request['new_password']);
        $user->save();
        return Redirect::back()->with(['success' => 'Your password has successfully been changed']);
    }
}
