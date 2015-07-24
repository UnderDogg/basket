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
use WNowicki\Generic\ApiClient\ErrorResponseException;

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
     * @param int $id
     * @param string $token
     * @return ApplicationEntity
     * @throws Exception
     */
    public function getApplication($id, $token)
    {
        return ApplicationEntity::make($this->fetchDocument('/v4/applications/' . $id, $token, 'Application'));
    }

    /**
     * @author WN
     * @param ApplicationEntity $application
     * @param string $token
     * @return ApplicationEntity
     * @throws Exception
     */
    public function initialiseApplication(ApplicationEntity $application, $token)
    {
        $api = $this->getApiFactory()->makeApiClient($token);

        try {
            $response = $api->post('/v4/initialize-application', $application->toArray(true));

            $application->setId($response['application']);
            $application->setResumeUrl($response['url']);

            return $application;

        } catch (ErrorResponseException $e) {

            $this->logWarning('ApplicationGateway::initialiseApplication[' . $e->getCode() . ']: ' . $e->getMessage());
            throw new Exception($e->getMessage());

        } catch (\Exception $e) {

            $this->logError('ApplicationGateway::initialiseApplication[' . $e->getCode() . ']: ' . $e->getMessage());
            throw new Exception('Problem Initialising Application on Provider API');
        }
    }

    /**
     * @author WN
     * @param int $id
     * @param string $token
     * @return bool
     * @throws Exception
     */
    public function fulfilApplication($id, $token)
    {
        $api = $this->getApiFactory()->makeApiClient($token);

        try {
            $api->post('/v4/applications/' . $id . '/fulfil');
            return true;

        } catch (ErrorResponseException $e) {

            $this->logWarning('ApplicationGateway::initialiseApplication[' . $e->getCode() . ']: ' . $e->getMessage());
            throw new Exception($e->getMessage());

        } catch (\Exception $e) {

            $this->logError('ApplicationGateway::initialiseApplication[' . $e->getCode() . ']: ' . $e->getMessage());
            throw new Exception('Problem Initialising Application on Provider API');
        }
    }
}
