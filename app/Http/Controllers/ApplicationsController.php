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
        return view(
            'applications.show',
            ['applications' => $this->fetchApplicationById($id), 'messages' => $this->getMessages()]
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
            'applications.edit',
            ['applications' => $this->fetchApplicationById($id), 'messages' => $this->getMessages()]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @author WN
     * @param int     $id
     * @param Request $request
     * @return Response
     * @throws RedirectException
     */
    public function update($id, Request $request)
    {
        return $this->updateModel((new Application()), $id, 'application', '/applications', $request);
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

    /**
     * @author WN
     * @param int $id
     * @return Application
     * @throws RedirectException
     */
    private function fetchApplicationById($id)
    {
        return $this->fetchModelById((new Application()), $id, 'application', '/applications');
    }
}
