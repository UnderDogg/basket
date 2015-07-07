<?php

namespace App\Http\Controllers;

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
		return view('role.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//$this->validate($request, ['name' => 'required']); // Uncomment and modify if needed.
		Role::create($request->all());
		return redirect('role');
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
        $role->permissionsAssoc = RolePermissions::where('role_id', '=', $role->id);

        $permissionIds = [];
        foreach ($role->permissionsAssoc as $assoc) {
            $permissionIds[] = $assoc->permission_id;
        }

        $role->permissions = Permission::whereIn('id', $permissionIds);

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
		//$this->validate($request, ['name' => 'required']); // Uncomment and modify if needed.
		$role = Role::findOrFail($id);
		$role->update($request->all());
		return redirect('role');
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
		return redirect('role');
	}

}
