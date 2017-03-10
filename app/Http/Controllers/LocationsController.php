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
use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
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
     * @param LocationStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function store(LocationStoreRequest $request)
    {
        try {
            $this->validateEmailAddressInput($request);
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
     * @param LocationUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws RedirectException
     */
    public function update($id, LocationUpdateRequest $request)
    {
        $converted_email = $request->has('converted_email') ? '1' : '0';
        $request->request->add(['converted_email' => $converted_email]);

        try {
            $this->validateEmailAddressInput($request);
        } catch (\Exception $e) {
            throw $this->redirectWithException(
                '/locations/' . $id . '/edit',
                'Cannot update Location: ' . $e->getMessage(),
                $e
            );
        }

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

    /**
     * Returns an array of fields and their types for filtering
     *
     * @author EB
     * @return array
     */
    protected function getFiltersConfiguration()
    {
        return [
            'id' => self::FILTER_STRICT,
            'installation_id' => self::FILTER_STRICT,
        ];
    }

    /**
     * @author EB
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    private function validateEmailAddressInput(Request $request)
    {
        $emails = explode(',', $request->get('email'));
        foreach ($emails as $email) {
            $initial = $email;
            if (!($email = filter_var($email, FILTER_VALIDATE_EMAIL))) {
                throw new \Exception('Cannot validate ' . $initial . ' as a valid email');
            }
        }

        return implode($emails);
    }
}
