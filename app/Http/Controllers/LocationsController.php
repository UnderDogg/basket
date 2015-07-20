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
use App\Basket\Location;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class LocationsController
 *
 * @author MS
 * @package App\Http\Controllers
 */
class LocationsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $messages = $this->getMessages();
        $locations = null;

        try {

            $locations = Location::query();

            if (!empty($filter = $this->getTableFilter())) {
                foreach ($filter as $field => $query) {

                    $locations->where($field, 'like', '%' . $query . '%');
                }
                if (!$locations->count()) {
                    $messages['info'] = 'No records were found that matched your filter';
                }
            }

            $locations = $locations->paginate($this->getPageLimit());

        } catch (ModelNotFoundException $e) {

            $this->logError('Error occurred getting locations: ' . $e->getMessage());
            $messages['error'] = 'Error occurred getting locations';

        }

        return View('locations.index', ['locations' => $locations, 'messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('locations.create', ['messages' => $this->getMessages()]);
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
            'email' => 'required:email',
            'address' => 'required',
            'reference' => 'required',
            'installation_id' => 'required',

        ]);

        $message = ['success','New Location has been successfully created'];

        try {

            Location::create($request->all());

        } catch (ModelNotFoundException $e) {

            $this->logError('Could not successfully create new Location' . $e->getMessage());
            $message = ['error','Could not successfully create new Location'];
        }

        return redirect('locations')->with($message[0], $message[1]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $locations = null;
        $messages = $this->getMessages();

        try {

            $locations = Location::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not find location with ID: [' . $id . ']; Location does not exist: ' . $e->getMessage()
            );
            $messages['error'] = 'Could not find location with ID: [' . $id . ']; Location does not exist';
        }

        return view('locations.show', ['location' => $locations, 'messages' => $messages]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $locations = null;
        $messages = $this->getMessages();

        try {

            $locations = Location::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not get Location with ID [' . $id . '] for editing; Location does not exist:' . $e->getMessage()
            );
            $messages['error'] = 'Could not get Location with ID [' . $id . '] for editing; Location does not exist';
        }

        return view('locations.edit', ['location' => $locations, 'messages' => $messages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required:email',
            'address' => 'required',
            'reference' => 'required',
            'installation_id' => 'required',

        ]);

        $message = ['success', 'Location details were successfully updated'];

        try {

            $locations = Location::findOrFail($id);
            $locations->update($request->all());

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not update Location with ID [' . $id . ']; Location does not exist' . $e->getMessage()
            );
            $message = ['error', 'Could not update Location with ID [' . $id . ']; Location does not exist'];
        }

        return redirect()->back()->with($message[0], $message[1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $message = ['success','locations was successfully deleted'];
        try {

            Location::destroy($id);

        } catch (ModelNotFoundException $e) {

            $this->logError('Deletion of this record did not complete successfully' . $e->getMessage());
            $message = ['error', 'Deletion of this record did not complete successfully'];
        }

        return redirect('locations')->with($message[0], $message[1]);
    }
}
