<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Applications;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApplicationsController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$applications = Applications::latest()->get();
		return view('applications.index', compact('applications'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('applications.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//$this->validate($request, ['name' => 'required']); // Uncomment and modify if needed.
		Applications::create($request->all());
		return redirect('applications');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$applications = Applications::findOrFail($id);
		return view('applications.show', compact('applications'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$applications = Applications::findOrFail($id);
		return view('applications.edit', compact('applications'));
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
		$applications = Applications::findOrFail($id);
		$applications->update($request->all());
		return redirect('applications');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Applications::destroy($id);
		return redirect('applications');
	}

}
