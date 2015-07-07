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
use App\User;
use Illuminate\Http\Request;

/**
 * Class UserController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @author MS
     * @return Response
     */
    public function index()
    {
        $user = User::latest()->get();
        return view('user.index', compact('user'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @author MS
     * @return Response 
     */
    public function create()
    {
        return view('user.create');
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
     * @author MS
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
     * @author MS
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
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
     * @author MS
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect('user');
    }

}
