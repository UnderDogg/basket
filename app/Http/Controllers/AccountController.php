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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                'user' => $this->getAuthenticatedUser(),
            ]
        );
    }

    /**
     * @author EB
     * @param Request $request
     * @return mixed
     * @throws RedirectException
     */
    public function update(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);
        $user->update($request->all());
        try {
            $user->update($request->all());
        } catch(\Exception $e) {
            $this->redirectWithException('/account/edit', 'Error while trying to update', $e);
        }
        return $this->redirectWithSuccessMessage(
            '/account/edit',
            'Your details have successfully been changed'
        );
    }

    /**
     * @author EB
     * @param Request $request
     * @return mixed
     * @throws RedirectException
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
            throw RedirectException::make('account/edit')->setError($e->getMessage());
        }

        try {
            $user->password = Hash::make($request['new_password']);
            $user->save();
        } catch(\Exception $e) {
            $this->logError('AccountController: Error while trying to change password: ' . $e->getMessage());
            throw RedirectException::make('/account/edit')->setError($e->getMessage());
        }
        return $this->redirectWithSuccessMessage(
            '/account/edit',
            'Your password has successfully been changed'
        );
    }
}
