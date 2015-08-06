<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PayBreak\Sdk\Gateways;

use App\Exceptions\Exception;
use PayBreak\Sdk\Entities\IpsEntity;

/**
 * Class IpsGateway
 *
 * @author EB
 * @package Paybreak\Sdk\Gateways
 */
class IpsGateway extends AbstractGateway
{
    /**
     * @author EB
     * @param string $token
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

    /**
     * @author EB
     * @param string $token
     * @param $ip
     * @return array
     * @throws Exception
     */
    public function storeIpAddress($token, $ip)
    {
        return $this->postDocument('/v4/ip-addresses', ['ip' => $ip], $token, 'ips');
    }

    /**
     * @author EB
     * @param string $token
     * @param int $id
     * @return array
     */
    public function deleteIpAddress($token, $id)
    {
        return $this->deleteDocument('/v4/ip-addresses/' . $id, $token, 'ips');
    }
}
