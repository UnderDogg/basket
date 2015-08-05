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
use App\Role;
use App\Permission;
use Illuminate\Http\Request;

/**
 * Class RolesController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class RolesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @author WN
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->standardIndexAction(Role::query(), 'role.index', 'role');
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
                'messages' => $this->getMessages(),
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
            $role = Role::create($request->all());
            $this->applyPermissions($role, $request);
        } catch (\Exception $e) {

            $this->logError('RolesController: Failed while storing new: ' . $e->getMessage());
            throw RedirectException::make('/roles')->setError('Could not save role');
        }

        return redirect('roles')->with('success', 'New role and role permissions were successfully created');
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
                'messages' => $this->getMessages(),
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
                'messages' => $this->getMessages(),
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
            $this->applyPermissions($role, $request);
        } catch (\Exception $e) {
            $this->logError('Could not update Role with ID [' . $id . ']: ' . $e->getMessage());
            throw RedirectException::make('/roles')->setError('Could not update Role');
        }

        return redirect()->back()->with('success', 'Roles and role permissions were successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author WN
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
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

        return view('includes.page.confirm_delete', ['object' => $role, 'messages' => $this->getMessages()]);
    }

    /**
     * @author WN
     * @param int $id
     * @return Role
     * @throws \App\Exceptions\RedirectException
     */
    private function fetchRoleById($id)
    {
        return $this->fetchModelById((new Role()), $id, 'role', '/roles');
    }

    /**
     * @author WN
     * @param Role $role
     * @param Request $request
     */
    private function applyPermissions(Role $role, Request $request)
    {
        if ($request->get('permissionsApplied')) {
            $ids = explode(':', $request->get('permissionsApplied'));
            array_shift($ids);
            $role->permissions()->sync($ids);
        }
    }
}
