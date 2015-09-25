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
        return $this->fetchCollection(
            $token,
            '/v4/installations',
            '\PayBreak\Sdk\Entities\InstallationEntity'
        );
    }

    /**
     * @author EB
     * @param $id
     * @param $body
     * @param $token
     * @return mixed
     */
    public function patchInstallation($id, $body, $token)
    {
        return $this->patchDocument('/v4/installations/' . $id, $token, 'updateInstallation', $body);
    }
}
