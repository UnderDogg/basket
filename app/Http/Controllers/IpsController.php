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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
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
    public function __construct(IpsGateway $ipsGateway) {

        $this->ipsGateway = $ipsGateway;
    }

    /**
     * @author EB
     * @return \Illuminate\View\View
     */
    public function index() {

        $messages = $this->getMessages();
        $ips = $this
            ->ipsGateway
            ->listIpAddresses($this->getMerchantToken());

        return view('merchants.ips', [
            'ips' => $ips,
            'messages' => $messages
        ]);
    }

    /**
     * @author EB
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request) {

        if(Validator::make($request->all(), ['ip' => 'ip'])->fails()) {
            return Redirect::back()->with(['error' => 'The IP address given is not a valid IP address']);
        }

        $response= $this->ipsGateway
            ->storeIpAddress($this->getMerchantToken(), $request->ip);
        return Redirect::back()->with(['success' => 'The IP address ' . $response['ip'] . ' has been created.']);
    }

    /**
     * @author EB
     * @param $id
     * @param $ip
     * @return mixed
     */
    public function delete($id, $ip) {

        $this->ipsGateway
            ->deleteIpAddress($this->getMerchantToken(), $id, $ip);
        return Redirect::back()->with(['success' => 'The IP address has been successfully deleted']);
    }
}
