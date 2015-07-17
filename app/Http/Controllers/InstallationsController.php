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
use App\Basket\Installation;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class InstallationController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class InstallationsController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $messages = $this->getMessages();
        $installations = null;

        try {

            $installations = Installation::query();

            if (!empty($filter = $this->getTableFilter())) {
                foreach ($filter as $field => $query) {

                    $installations->where($field, 'like', '%' . $query . '%');
                }
                if (!$installations->count()) {
                    $messages['info'] = 'No records were found that matched your filter';
                }
            }

            $installations = $installations->paginate($this->getPageLimit());

        } catch (ModelNotFoundException $e) {

            $this->logError('Error occurred getting installations: ' . $e->getMessage());
            $messages['error'] = 'Error occurred getting installations';

        }

        return View('installations.index', ['installations' => $installations, 'messages' => $messages]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

        $installations = null;
        $messages = $this->getMessages();

        try {

            $installations = Installation::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not find installation with ID: [' . $id . ']; installation does not exist: ' . $e->getMessage()
            );
            $messages['error'] = 'Could not find installation with ID: [' . $id . ']; installation does not exist';
        }

        return view('installations.show', ['installations' => $installations, 'messages' => $messages]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $installations = null;
        $messages = $this->getMessages();

        try {

            $installations = Installation::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not get installation with ID [' . $id . '] for editing; installation does not exist:' . $e->getMessage()
            );
            $messages['error'] = 'Could not get installation with ID [' . $id . '] for editing; installation does not exist';
        }

        return view('installations.edit', ['installations' => $installations, 'messages' => $messages]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $message = ['success', 'Installation details were successfully updated'];

        try {

            $installations = Installation::findOrFail($id);
            $installations->update($request->all());

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not update installation with ID [' . $id . ']; installation does not exist' . $e->getMessage()
            );
            $message = ['error', 'Could not update installation with ID [' . $id . ']; installation does not exist'];
        }

        return redirect()->back()->with($message[0], $message[1]);
	}
}
