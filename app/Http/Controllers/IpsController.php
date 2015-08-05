<?php

namespace App\Http\Controllers;

use App\Basket\Application;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PayBreak\Sdk\Gateways\IpsGateway;

class IpsController extends Controller {

    protected $ipsGateway;

    public function __construct(IpsGateway $ipsGateway) {
        $this->ipsGateway = $ipsGateway;
    }

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

    public function store(Request $request) {
        $ip = $request->ip;
        $response= $this->ipsGateway
            ->storeIpAddress($this->getMerchantToken(), $ip);
        return Redirect::back()->with(['success' => 'The IP address ' . $response['ip'] . ' has been created.']);
    }

    public function delete($id, $ip) {
        $this->ipsGateway
            ->deleteIpAddress($this->getMerchantToken(), $id, $ip);
        return Redirect::back()->with(['success' => 'The IP address has been successfully deleted']);
    }
}