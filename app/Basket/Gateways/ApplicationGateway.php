<?php
/*
* This file is part of the PayBreak/basket package.
*
* (c) PayBreak <dev@paybreak.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace App\Basket\Gateways;

use App\Basket\Entities\ApplicationEntity;
use App\Exceptions\Exception;

/**
 * Application Gateway
 *
 * @author WN
 * @package App\Basket\Gateways
 */
class ApplicationGateway extends AbstractGateway
{
    /**
     * @author WN
     * @param string $id
     * @param string $token
     * @return ApplicationEntity
     * @throws Exception
     */
    public function getInstallation($id, $token)
    {
        return ApplicationEntity::make($this->fetchDocument('/v4/applications/' . $id, $token, 'Application'));
    }
}
