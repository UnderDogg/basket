<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Basket\Applications;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApplicationsController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $messages = $this->getMessages();
        $applications = null;

        try {

            $applications = Applications::query();

            if (!empty($filter = $this->getTableFilter())) {
                foreach ($filter as $field => $query) {

                    $applications->where($field, 'like', '%' . $query . '%');
                }
                if (!$applications->count()) {
                    $messages['info'] = 'No records were found that matched your filter';
                }
            }

            $applications = $applications->paginate($this->getPageLimit());

        } catch (ModelNotFoundException $e) {

            $this->logError('Error occurred getting applications: ' . $e->getMessage());
            $messages['error'] = 'Error occurred getting applications';

        }
        return View('applications.index', ['applications' => $applications, 'messages' => $messages]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $applications = null;
        $messages = $this->getMessages();

        try {

            $applications = Applications::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not find application with ID: [' . $id . ']; Application does not exist: ' . $e->getMessage()
            );
            $messages['error'] = 'Could not find application with ID: [' . $id . ']; Application does not exist';
        }

        return view('applications.show', ['applications' => $applications, 'messages' => $messages]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $applications = null;
        $messages = $this->getMessages();

        try {

            $applications = Applications::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not get application with ID [' . $id . '] for editing; Application does not exist:' .
                $e->getMessage()
            );
            $messages['error'] =
                'Could not get application with ID [' .
                $id .
                '] for editing; Application does not exist';
        }

        return view('applications.edit', ['applications' => $applications, 'messages' => $messages]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $message = ['success', 'Application details were successfully updated'];

        try {

            $applications = Applications::findOrFail($id);
            $applications->update($request->all());

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not update application with ID [' . $id . ']; Application does not exist' . $e->getMessage()
            );
            $message = ['error', 'Could not update application with ID [' . $id . ']; Application does not exist'];
        }

        return redirect()->back()->with($message[0], $message[1]);
	}
}
