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
use App\Permission;
use App\Role;
use Illuminate\Http\Request;

/**
 * Class RolesController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class RolesController extends Controller
{
    const SUPER_USER_NAME = 'su';
    const READ_ONLY_NAME = 'rosu';

    /**
     * Display a listing of the resource.
     *
     * @author WN
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->standardIndexAction(Role::query(), 'role.index', 'roles');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author WN
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view(
            'role.create',
            [
                'permissionsAvailable' => Permission::all(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author WN, MS
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);

        try {
            $role = Role::create(
                $request->only(
                    'name', 'display_name', 'description'
                )
            );
            $role->permissions()->sync(
                array_values(
                    $request->except(
                        '_token', 'name', 'display_name', 'description', 'createRoleButton'
                    )
                )
            );
        } catch (\Exception $e) {
            return $this->redirectWithException('/roles/', 'Failed while storing new', $e);
        }
        return $this
            ->redirectWithSuccessMessage(
                'roles',
                'New roles and role permissions were successfully created'
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
        return view(
            'role.show',
            [
                'role' => $this->fetchRoleById($id),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @author WN
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = $this->fetchRoleById($id);

        return view(
            'role.edit',
            [
                'role' => $role,
                'permissionsAvailable' => Permission::all()->diff($role->permissions),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'display_name'  => 'required',
        ]);

        try {
            $role = $this->fetchRoleById($id);
            $role->update($request->all());
            $role->permissions()->sync(
                array_values(
                    $request->except(
                        '_method', '_token', 'name', 'display_name', 'description', 'saveChanges'
                    )
                )
            );
        } catch (\Exception $e) {
            $this->logError('Could not update Role with ID [' . $id . ']: ' . $e->getMessage());
            throw RedirectException::make('/roles')->setError('Could not update Role');
        }
        return $this->redirectWithSuccessMessage(
            'roles',
            'Roles and permissions were successfully updated'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author WN, EB
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function destroy($id)
    {
        $role = $this->fetchRoleById($id);

        if ($role->name == self::SUPER_USER_NAME || $role->name == self::READ_ONLY_NAME) {
            throw RedirectException::make('/')->setError('Cannot delete ' . $role->name . ', it\'s a special role!');
        }

        return $this->destroyModel((new Role()), $id, 'role', '/roles');
    }

    /**
     * Delete
     *
     * @author WN, MS
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function delete($id)
    {
        $role = $this->fetchRoleById($id);
        $role->type = 'roles';
        $role->controller = 'Roles';

        return view('includes.page.confirm_delete', ['object' => $role]);
    }
}
