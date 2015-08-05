<?php

namespace Paybreak\Sdk\Gateways;

use App\Exceptions\Exception;
use PayBreak\Sdk\Gateways\AbstractGateway;
use PayBreak\Sdk\Entities\IpsEntity;
use WNowicki\Generic\ApiClient\ErrorResponseException;

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

        foreach ($response as $ip) {
            $rtn[] = IpsEntity::make($ip);
        }

        return $rtn;
    }

    public function storeIpAddress($token, $ip) {
        return $this->storeDocument('/v4/ip-addresses', ['ip' => $ip], $token, 'ips');
    }

    /**
     * @author EB
     * @param $token
     * @param $merchantId
     * @param $ip
     * @return array
     */
    public function deleteIpAddress($token, $merchantId, $ip)
    {
        try{
            return $this->deleteDocument('/v4/ip-addresses/' . $ip, $token, 'ips');
        } catch (\Exception $e) {
            var_dump('Merchant with ID '.$merchantId.' could not complete process',$e->getMessage(), $e->getCode());
            die('Exception');
        }
    }

}