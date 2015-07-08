<?php

namespace App\Http\Controllers;

use App\Exceptions\Exception;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use App\RolePermissions;
use App\Permission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoleController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$role = Role::latest()->get();
		return view('role.index', compact('role'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $permissionsAvailable = Permission::all();
		return view('role.create', compact('permissionsAvailable'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//$this->validate($request, ['name' => 'required']); // Uncomment and modify if needed.
		$role = Role::create($request->all());

        $this->updateAllRolePermissions($role->id, $request);

		return redirect('role')->with('message','New role and role permissions were successfully created');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$role = Role::findOrFail($id);
		return view('role.show', compact('role'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $role = Role::findOrFail($id);
        $role->message = session()->get('message');

        $role = $this->assignPermissionsToRole($role);

		return view('role.edit', compact('role'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $role = Role::findOrFail($id);
        $role->update($request->all());

        $this->updateAllRolePermissions($role->id, $request);

		return redirect()->back()->with('message','Roles and role permissions were successfully updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Role::destroy($id);
		return redirect('role')->with('message','Role was successfully deleted');
	}

    /**
     * Update All Role Permissions
     *
     * @author MS
     * @param $id
     * @param mixed $request
     * @return mixed
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
    private function assignPermissionsToRole(Role $role)
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
