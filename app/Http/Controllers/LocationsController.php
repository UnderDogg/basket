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

use App\Exceptions\RedirectException;
use App\Basket\Installation;
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
     * @author WN, MS
     * @return Response
     */
    public function index()
    {
        $locations = Location::query();
        $this->limitToInstallationOnMerchant($locations);
        return $this->standardIndexAction($locations, 'locations.index', 'locations');
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
        return view(
            'locations.show',
            ['location' => $this->fetchLocationById($id), 'messages' => $this->getMessages()]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view(
            'locations.edit',
            [
                'location' => $this->fetchLocationById($id),
                'installations' => Installation::query()->get(),
                'messages' => $this->getMessages()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     * @throws RedirectException
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

        $locations = $this->fetchLocationById($id);
        try {
            $toUpdate = $request->all();
            $toUpdate['active'] = ($request->has('active')) ? 1 : 0;
            $locations->update($toUpdate);
        } catch (\Exception $e) {
            $this->logError('Can not update location [' . $id . ']: ' . $e->getMessage());
            throw (new RedirectException())->setTarget('/locations/' . $id . '/edit')->setError($e->getMessage());
        }

        return redirect()->back()->with('success', 'Location details were successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->destroyModel((new Location()), $id, 'location', '/locations');
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
        $location = $this->fetchLocationById($id);
        $location->type = 'location';
        $location->controller = 'Locations';
        return view('includes.page.confirm_delete', ['object' => $location, 'messages' => $this->getMessages()]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\RedirectException]
     */
    private function fetchLocationById($id)
    {
        $location = $this->fetchModelById((new Location()), $id, 'location', '/locations');

        if (\Auth::user()->merchant_id) {
            if ($location->installation->merchant->id != \Auth::user()->merchant_id) {
                throw RedirectException::make('/locations')
                    ->setError('You are not allowed to take any action on this Location');
            }
        }

        return $location;
    }
}
