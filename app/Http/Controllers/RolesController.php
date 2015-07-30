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

use App\Http\Requests;
use App\Role;
use App\RolePermissions;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * @author WN, MS
     * @return Response
     */
    public function index()
    {
        return $this->standardIndexAction(Role::query(), 'role.index', 'role');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author MS
     * @return Response
     */
    public function create()
    {
        $permissionsAvailable = null;
        $messages = $this->getMessages();

        try {

            $permissionsAvailable = Permission::all();

        } catch (ModelNotFoundException $e) {

            $this->logError('Error occurred getting available permissions: ' . $e->getMessage());
            $messages['error'] = 'Error occurred getting available permissions';
        }

        return view('role.create', [
            'permissionsAvailable' => $permissionsAvailable,
            'messages' => $messages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author MS
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);

        $message = ['success','New role and role permissions were successfully created'];

        try {

            $role = Role::create($request->all());

            if (!$this->updateAllRolePermissions($role->id, $request)) {
                $message = [
                    'info',
                    'Role was created successfully, but you have not applied any permissions for this role!'
                ];
            }

        } catch (ModelNotFoundException $e) {

            $this->logError('Could not successfully create new Role' . $e->getMessage());
            $message = ['error','Could not successfully create new Role'];
        }

        return redirect('roles')->with($message[0], $message[1]);
    }

    /**
     * Display the specified resource.
     *
     * @author MS
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $role = null;
        $messages = $this->getMessages();

        try {

            $role = Role::findOrFail($id);
            $role = $this->fetchPermissionsToRole($role);

        } catch (ModelNotFoundException $e) {

            $this->logError('Could not find Role with ID: [' . $id . ']; Role does not exist: ' . $e->getMessage());
            $messages['error'] = 'Could not find Role with ID: [' . $id . ']; Role does not exist';
        }

        return view('role.show', ['role' => $role, 'messages' => $messages]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @author MS
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $role = null;
        $messages = $this->getMessages();

        try {

            $role = Role::findOrFail($id);
            $role = $this->fetchPermissionsToRole($role);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not get Role with ID [' . $id . '] for editing; Role does not exist:' . $e->getMessage()
            );
            $messages['error'] = 'Could not get Role with ID [' . $id . '] for editing; Role does not exist';
        }

        return view('role.edit', ['role' => $role, 'messages' => $messages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @author MS
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $message = ['success', 'Roles and role permissions were successfully updated'];

        $role = $this->fetchRoleById($id);

        try {
            $role->update($request->all());
            $this->updateAllRolePermissions($role->id, $request);

        } catch (ModelNotFoundException $e) {

            $this->logError('Could not update Role with ID [' . $id . ']; Role does not exist' . $e->getMessage());
            $message = ['error', 'Could not update Role with ID [' . $id . ']; Role does not exist'];

        } catch (\App\Exceptions\Exception $ex) {

            $this->logError($ex->getMessage());
            $message = ['error', $ex->getMessage()];
        }

        return redirect()->back()->with($message[0], $message[1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author WN
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->destroyModel((new Role()), $id, 'role', '/roles');
    }

    /**
     * Update All Role Permissions
     *
     * @author MS
     * @param $id
     * @param mixed $request
     * @return bool
     * @throws \App\Exceptions\Exception
     */
    private function updateAllRolePermissions($id, $request)
    {
        if (empty($id) || !is_int($id)) {
            throw new \App\Exceptions\Exception(
                'Could not update Permissions; ID [' . $id . '] does not exist or is malformed'
            );
        }
        if (empty($request)) {
            throw new \App\Exceptions\Exception(
                'Could not update Role Permission for Role with ID [' . $id . ']; Request empty of malformed'
            );
        }

        $rolePermissionsToUpdate = explode(':', $request['permissionsApplied']);
        unset($rolePermissionsToUpdate[0]);

        $rolePermissions = RolePermissions::where('role_id', '=', $id)->get();

        foreach ($rolePermissions as $permission) {
            if (!in_array($permission['permission_id'], $rolePermissionsToUpdate)) {

                try {

                    RolePermissions::where('role_id', '=', $id)
                        ->where('permission_id', '=', $permission['permission_id'])
                        ->delete();

                } catch (ModelNotFoundException $e) {

                    throw $e;
                }
            }
            if(($key = array_search($permission['permission_id'], $rolePermissionsToUpdate)) !== false) {
                unset($rolePermissionsToUpdate[$key]);
            }
        }

        if (count($rolePermissionsToUpdate) > 0) {
            foreach ($rolePermissionsToUpdate as $permission) {

                try {

                    RolePermissions::create(['permission_id' => $permission, 'role_id' => $id]);

                } catch (ModelNotFoundException $e) {

                    throw $e;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Assign Permissions To Role
     *
     * @author MS
     * @param Role $role
     * @return Role
     */
    private function fetchPermissionsToRole($role)
    {
        if (!empty($role)) {
            $role->permissionsAssociation = RolePermissions::where('role_id', '=', $role->id)->get();

            $permissionIds = [];
            foreach ($role->permissionsAssociation as $association) {
                $permissionIds[] = $association->permission_id;
            }

            $role->permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->permissionsAvailable = Permission::whereNotIn('id', $permissionIds)->get();
        }
        return $role;
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
        $role = null;
        $messages = $this->getMessages();

        try {

            $role = Role::findOrFail($id);
            $role->type = 'roles';
            $role->controller = 'Roles';

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not get role with ID: [' . $id . ']; Role does not exist: ' . $e->getMessage()
            );
            $messages['error'] = 'Could not get role with ID: [' . $id . ']; Role does not exist';
        }

        return view('includes.page.confirm_delete', ['object' => $role, 'messages' => $messages]);
    }

    private function fetchRoleById($id)
    {
        return $this->fetchModelById((new Role()), $id, 'role', '/roles');
    }
}