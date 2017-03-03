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
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateAccountRequest;
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
     * @param string $view
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function update(UpdateAccountRequest $request)
    {
        $user = $this->getAuthenticatedUser();
        try {
            $user->update($request->all());
        } catch (\Exception $e) {
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $this->getAuthenticatedUser();

        if (!Hash::check($request->get("old_password"), $user->getAuthPassword())) {
            throw RedirectException::make('account/edit')->setError('Old password must match stored password');
        }

        try {
            $user->password = Hash::make($request['new_password']);
            $user->save();
        } catch (\Exception $e) {
            $this->logError('AccountController: Error while trying to change password: ' . $e->getMessage());
            throw RedirectException::make('/account/edit')->setError($e->getMessage());
        }
        return $this->redirectWithSuccessMessage(
            '/account/edit',
            'Your password has successfully been changed'
        );
    }
}
