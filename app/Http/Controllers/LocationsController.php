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
        return $this->standardIndexAction(
            $locations,
            'locations.index',
            'locations',
            [
                'active' => $this->fetchBooleanFilterValues($locations, 'active', 'Inactive', 'Active'),
                'installation_id' => $this->fetchAssociateFilterValues($locations, 'installation'),
            ]
        );
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
            'reference' => 'required|regex:/^[A-Za-z0-9\-!]+$/',
            'installation_id' => 'required',
            'active' => 'required',
            'name' => 'required',
            'email' => 'required:email',
            'address' => 'required',
        ]);

        try {
            $toCreate = $request->all();
            $toCreate['active'] = ($request->has('active')) ? 1 : 0;
            Location::create($toCreate);
        } catch (\Exception $e) {
            $this->logError('Could not successfully create new Location' . $e->getMessage());
            throw RedirectException::make('/locations/')->setError($e->getMessage());
        }
        return $this->redirectWithSuccessMessage(
            'locations',
            'New location has been successfully created'
        );
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
            'reference' => 'required|regex:/^[A-Za-z0-9\-!]+$/',
            'active' => 'required',
            'name' => 'required',
            'email' => 'required:email',
            'address' => 'required',
        ]);

        return $this->updateModel((new Location()), $id, 'location', '/locations', $request);
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
