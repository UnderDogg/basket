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

use PayBreak\Sdk\Entities\ApplicationEntity;
use App\Exceptions\Exception;
use WNowicki\Generic\ApiClient\ErrorResponseException;
use Illuminate\Support\Collection;

/**
 * Application Gateway
 *
 * @author WN
 * @package PayBreak\Sdk\Gateways
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
        return $this->requestAction('/v4/applications/' . $id . '/fulfil', [], $token);
    }

    /**
     * @param int $id
     * @param string $description
     * @param string $token
     * @return bool
     * @throws Exception
     */
    public function cancelApplication($id, $description, $token)
    {
        return $this->requestAction(
            '/v4/applications/' . $id . '/request-cancellation',
            ['description' => $description],
            $token
        );
    }


    /**
     * @param $token
     * @return array
     * @throws Exception
     */
    public function getPendingCancellations($token)
    {

        $test = json_decode('
           [{
    "id": 123,
    "posted_date": "2015-03-17T15:18:00Z",
    "current_status": "converted",
    "customer": {
        "title": "Mr",
        "first_name": "Fillibert",
        "last_name": "Labingi",
        "email_address": "fillibertlabingi+paybreak@gmail.com",
        "phone_home": null,
        "phone_mobile": "07700900124",
        "postcode": "TN12 6ZZ"
    },
    "application_address": {
        "abode": "Flat 2A",
        "building_name": "",
        "building_number": "1",
        "street": "Newtown Walk",
        "locality": "",
        "town": "Walmington-on-Sea",
        "postcode": "TN12 6ZZ"
    },
    "installation": "NoveltyRock",
    "order": {
        "reference": "NRE01234",
        "amount": 0,
        "description": "",
        "validity": ""
    },
    "products": {
        "group": "FF",
        "options": [
            "*"
        ],
        "default": "FF/1-3"
    },
    "fulfilment": {
        "method": "collection",
        "location": "Walmington-on-Sea Store"
    },
    "applicant": {
        "title": "Mr",
        "first_name": "Fillibert",
        "last_name": "Labingi",
        "date_of_birth": "1970-01-01",
        "email_address": "fillibert.labingi@gmail.com",
        "phone_home": null,
        "phone_mobile": "07700900123",
        "postcode": "TN12 6ZZ"
    },
    "finance": {
        "loan_amount": 0,
        "order_amount": 0,
        "deposit_amount": 0,
        "subsidy_amount": 0,
        "settlement_net_amount": 0
    },
    "metadata": {
        "you": "do",
        "what_ever": "you",
        "want": 2
    },
    "cancellation" : {
        "requested": true,
        "effective_date": "2015-03-18",
        "requested_date": "2015-03-18T12:00:00+01:00",
        "description": "Items are out of stock and the customer wishes to cancel"
    }
}]', true);


        $test = Collection::make($test);
        Collection::


        $id = 'TestInstall';
        return $this->fetchDocument(
            '/v4/installations/' . $id . '/applications',
            $token,
            'Pending Cancellations',
            ['pending-cancellations' => true]
        );
    }


    /**
     * @author WN
     * @param string $action
     * @param array $data
     * @param string $token
     * @return bool
     * @throws Exception
     */
    private function requestAction($action, $data, $token)
    {
        $this->postDocument($action, $data, $token, 'Application');
        return true;
    }
}
