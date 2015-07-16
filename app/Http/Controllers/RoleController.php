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
use Illuminate\Support\Facades\Auth;

/**
 * Class RoleController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @author MS
     * @return Response
     */
    public function index()
    {
        $updatedMessage = $this->getMessages();
        $role = Role::query();

        if (!empty($filter = $this->getTableFilter())) {
            foreach ($filter as $field => $query) {

                $role->where($field, 'like', '%' . $query . '%');
            }
            if (!$role->count()) {
                $updatedMessage['info'] = 'No records were found that matched your filter';
            }
        }

        $role = $role->paginate($this->getPageLimit());
        return View('role.index', ['role' => $role, 'messages' => $updatedMessage]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author MS
     * @return Response
     */
    public function create()
    {
        $permissionsAvailable = Permission::all();
        return view('role.create', [
            'permissionsAvailable' => $permissionsAvailable,
            'messages' => $this->getMessages()
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
        $role = Role::create($request->all());
        $this->updateAllRolePermissions($role->id, $request);

        return redirect('role')->with('success','New role and role permissions were successfully created');
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

        } catch (ModelNotFoundException $e) {

            $this->logError($e->getMessage());
            $messages['error'] = 'Could not find Role with ID: [' . $id . ']; Role doesn\'t exist';
        }

        $role = $this->fetchPermissionsToRole($role);

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
        $role = Role::findOrFail($id);
        $role = $this->fetchPermissionsToRole($role);

        return view('role.edit', ['role' => $role, 'messages' => $this->getMessages()]);
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
        $role = Role::findOrFail($id);
        $role->update($request->all());

        $this->updateAllRolePermissions($role->id, $request);

        return redirect()->back()->with('success','Roles and role permissions were successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author MS
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Role::destroy($id);
        RolePermissions::where('role_id', '=', $id)->delete();
        return redirect('role')->with('success','Role was successfully deleted');
    }

    /**
     * Update All Role Permissions
     *
     * @author MS
     * @param $id
     * @param mixed $request
     */
    private function updateAllRolePermissions($id, $request)
    {
        $rolePermissionsToUpdate = explode(':', $request['permissionsApplied']);
        unset($rolePermissionsToUpdate[0]);

        $rolePermissions = RolePermissions::where('role_id', '=', $id)->get();

        foreach ($rolePermissions as $permission) {
            if (!in_array($permission['permission_id'], $rolePermissionsToUpdate)) {
                RolePermissions::where('role_id', '=', $id)
                    ->where('permission_id', '=', $permission['permission_id'])
                    ->delete();
            }
            if(($key = array_search($permission['permission_id'], $rolePermissionsToUpdate)) !== false) {
                unset($rolePermissionsToUpdate[$key]);
            }
        }

        if (count($rolePermissionsToUpdate) > 0) {
            foreach ($rolePermissionsToUpdate as $permission) {
                RolePermissions::create(['permission_id' => $permission, 'role_id' => $id]);
            }
        }
    }

    /**
     * Assign Permissions To Role
     *
     * @author MS
     * @param Role $role
     * @return Role
     */
    private function fetchPermissionsToRole(Role $role)
    {
        $role->permissionsAssociation = RolePermissions::where('role_id', '=', $role->id)->get();

        $permissionIds = [];
        foreach ($role->permissionsAssociation as $association) {
            $permissionIds[] = $association->permission_id;
        }

        $role->permissions = Permission::whereIn('id', $permissionIds)->get();
        $role->permissionsAvailable = Permission::whereNotIn('id', $permissionIds)->get();

        return $role;
    }
}
