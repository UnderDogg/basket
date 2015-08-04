<?php

namespace App\Http\Controllers;

use App\Basket\Application;
use App\Http\Requests;
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
            ->listIpAddresses('a702ae4ad59e47f5991cf4857bb75055');

        return view('merchants.ips', [
            'ips' => $ips,
            'messages' => $messages
        ]);
    }

    public function store(Request $request) {
        dd($request);
    }

    public function delete() {
        $messages = $this->getMessages();
    }
}