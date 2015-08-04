<?php

namespace Paybreak\Sdk\Gateways;

use App\Exceptions\Exception;
use PayBreak\Sdk\Gateways\AbstractGateway;
use PayBreak\Sdk\Entities\IpsEntity;

class IpsGateway extends AbstractGateway
{
    /**
     * @param $token
     * @return IpsEntity[]
     * @throws Exception
     */
    public function listIpAddresses($token)
    {
        $response = $this->fetchDocument('/v4/ip-addresses',$token, 'ips');

        $rtn = [];

        //var_dump($response);die;

        foreach ($response as $ip) {
            $rtn[] = IpsEntity::make($ip);
        }

        return $rtn;
    }
}