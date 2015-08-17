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

use App\Basket\Installation;
use App\Basket\Location;
use App\Exceptions\RedirectException;
use Illuminate\Http\Request;

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
     * @return \Illuminate\View\View
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
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->renderForm('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required:email',
            'address' => 'required',
            'reference' => 'required',
            'installation_id' => 'required',
            'active' => 'required'

        ]);

        try {
            $toCreate = $request->all();
            $toCreate['active'] = ($request->has('active')) ? 1 : 0;
            Location::create($toCreate);
            return redirect()->back()->with('messages', ['success' => 'New Locations has been successfully created']);

        } catch (\Exception $e) {
            $this->logError('Could not successfully create new Location' . $e->getMessage());
            throw RedirectException::make('/locations/')->setError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view(
            'locations.show',
            ['location' => $this->fetchLocationById($id)]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        return $this->renderForm('edit', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
            throw RedirectException::make('/locations/' . $id . '/edit')->setError($e->getMessage());
        }
        return redirect('/locations/'.$id.'/edit')->with('messages', ['success' => 'Location details were successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
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
        return view('includes.page.confirm_delete', ['object' => $location]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\RedirectException]
     */
    private function fetchLocationById($id)
    {
        return $this->fetchModelByIdWithInstallationLimit((new Location()), $id, 'location', '/locations');
    }

    /**
     * @author WN
     * @param string $template
     * @param integer $id
     * @return \Illuminate\View\View
     */
    private function renderForm($template, $id = null)
    {
        return view(
            'locations.' . $template,
            [
                'location' => $id !== null?$this->fetchLocationById($id):null,
                'installations' => $this->limitToActive($this->limitToMerchant(Installation::query()))
                    ->get()->pluck('name', 'id')->toArray(),
            ]
        );
    }
}
