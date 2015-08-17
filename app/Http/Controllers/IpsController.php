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
     * @throws RedirectException
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
            $this->logError('IpsController: Trying to get IP\'s failed: ' . $e->getMessage());
            throw RedirectException::make('/merchants/')->setError($e->getMessage());
        }
    }

    /**
     * @author EB
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws RedirectException
     */
    public function store($id, Request $request)
    {
        $this->validate($request, ['ip' => 'required|ip']);
        try {
            $response= $this->ipsGateway
                ->storeIpAddress($this->fetchMerchantById($id)->token, $request->ip);
        } catch(\Exception $e) {
            $this->logError('IpsController: Error while trying to create a new IP: ' . $e->getMessage());
            throw RedirectException::make('/merchants/'.$id.'/ips')->setError($e->getMessage());
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
     * @throws RedirectException
     */
    public function delete($id, $ip)
    {
        try {
            $this->ipsGateway
                ->deleteIpAddress($this->fetchMerchantById($id)->token, $ip);
        } catch(\Exception $e) {
            $this->logError("IpsController: Error while trying to delete an IP address: " . $e->getMessage());
            throw RedirectException::make('/merchants/'.$id.'/ips')->setError($e->getMessage());
        }
        return $this->redirectWithSuccessMessage(
            'merchants/'.$id.'ips',
            'IP address successfully deleted'
        );
    }
}
