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
use App\Basket\Application;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ApplicationsController
 *
 * @author MS
 * @package App\Http\Controllers
 */
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

            $applications = Application::query();

            if (!empty($filter = $this->getTableFilter())) {
                foreach ($filter as $field => $query) {

                    if (is_numeric($query)) {
                        $query = $this->reformatForCurrency($field, $query);
                    }
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

            $applications = Application::findOrFail($id);

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

            $applications = Application::findOrFail($id);

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

            $applications = Application::findOrFail($id);
            $applications->update($request->all());

        } catch (ModelNotFoundException $e) {

            $this->logError(
                'Could not update application with ID [' . $id . ']; Application does not exist' . $e->getMessage()
            );
            $message = ['error', 'Could not update application with ID [' . $id . ']; Application does not exist'];
        }

        return redirect()->back()->with($message[0], $message[1]);
	}

    /**
     * Reformat For Currency
     *
     * @author MS
     * @param string $field
     * @param int|float $integer
     * @return int
     */
    private function reformatForCurrency($field, $integer)
    {
        if (
            !($field === 'ext_order_amount') &&
            !($field === 'ext_finance_order_amount') &&
            !($field === 'ext_finance_loan_amount') &&
            !($field === 'ext_finance_deposit') &&
            !($field === 'ext_finance_subsidy') &&
            !($field === 'ext_finance_net_settlement')
        ) {

            return $integer;
        }
        return round($integer * 100);
    }
}
