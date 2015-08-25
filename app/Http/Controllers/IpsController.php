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
use Illuminate\Http\Request;
use PayBreak\Sdk\Gateways\IpsGateway;

/**
 * Class IpsController
 *
 * @author EB
 * @package App\Http\Controllers
 */
class IpsController extends Controller
{
    protected $ipsGateway;

    /**
     * @author EB
     * @param IpsGateway $ipsGateway
     */
    public function __construct(IpsGateway $ipsGateway)
    {
        $this->ipsGateway = $ipsGateway;
    }

    /**
     * @author EB
     * @param $id
     * @return \Illuminate\View\View
     * @throws IpsController
     */
    public function index($id)
    {
        try {
            $ips = $this
                ->ipsGateway
                ->listIpAddresses($this->fetchMerchantById($id)->token);
            return view('merchants.ips', [
                'ips' => $ips,
            ]);
        } catch(\Exception $e) {
            throw $this->redirectWithException('/merchants/','Trying to get IP\'s failed', $e);
        }
    }

    /**
     * @author EB
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws IpsController
     */
    public function store($id, Request $request)
    {
        $this->validate($request, ['ip' => 'required|ip']);
        try {
            $response= $this->ipsGateway
                ->storeIpAddress($this->fetchMerchantById($id)->token, $request->ip);
        } catch(\Exception $e) {
            throw $this->redirectWithException('/merchants/'.$id.'/ips', 'Error while trying to create a new IP', $e);
        }
        return $this->redirectWithSuccessMessage(
            '/merchants/'.$id.'/ips',
            'IP address \'' . $response['ip'] . '\' created.'
        );
    }

    /**
     * @author EB
     * @param $id
     * @param $ip
     * @return mixed
     * @throws IpsController
     */
    public function delete($id, $ip)
    {
        try {
            $this->ipsGateway
                ->deleteIpAddress($this->fetchMerchantById($id)->token, $ip);
        } catch(\Exception $e) {
            throw $this->redirectWithException(
                '/merchants/'.$id.'/ips',
                'Error while trying to delete an IP address',
                $e)
            ;
        }
        return $this->redirectWithSuccessMessage(
            'merchants/'.$id.'ips',
            'IP address successfully deleted'
        );
    }
}