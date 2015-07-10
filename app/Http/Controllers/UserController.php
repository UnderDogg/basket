<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use League\Flysystem\Exception;

class UserController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        /*
         * Just incase you forget
         * {{ Auth::user()->email }}
         */


		$user = User::latest()->get();
        $user->message = session()->get('message');
		return view('user.index', compact('user'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('user.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'merchant' => 'required',
        ]);
        $array = $request->all();

        $array['password'] = bcrypt($array['password']);
        User::create($array);
        return redirect('user');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::findOrFail($id);
		return view('user.show', compact('user'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::findOrFail($id);
        $user->message = session()->get('message');
		return view('user.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$this->validate($request, ['name' => 'required']); // Uncomment and modify if needed.
		$user = User::findOrFail($id);

        $array = $request->all();

        if (empty($array['password'])) {
            // Create Error To Show User
        }

        $array['password'] = bcrypt($array['password']);
		$user->update($array);
		return redirect('user');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		User::destroy($id);
		return redirect('user');
	}

}
