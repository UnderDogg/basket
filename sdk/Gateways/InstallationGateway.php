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

use PayBreak\Sdk\Entities\InstallationEntity;
use App\Exceptions\Exception;
use WNowicki\Generic\ApiClient\ErrorResponseException;

/**
 * Installation Gateway
 *
 * @author WN
 * @package PayBreak\Sdk\Gateways
 */
class InstallationGateway extends AbstractGateway
{
    /**
     * @param string $id
     * @param string $token
     * @return InstallationEntity
     * @throws Exception
     */
    public function getInstallation($id, $token)
    {
        return InstallationEntity::make($this->fetchDocument('/v4/installations/' . $id, $token, 'Installation'));
    }

    /**
     * @author WN
     * @param $token
     * @return InstallationEntity[]
     * @throws Exception
     */
    public function getInstallations($token)
    {
        $installations = $this->fetchDocument('/v4/installations', $token, 'installations');

        $rtn = [];

        foreach ($installations as $installation) {
            $rtn[] = InstallationEntity::make($installation);
        }

        return $rtn;
    }
}
