<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Synchronisation;

use App\Basket\Application;
use App\Basket\Entities\Application\AddressEntity;
use App\Basket\Entities\Application\ApplicantEntity;
use App\Basket\Entities\Application\CustomerEntity;
use App\Basket\Entities\Application\OrderEntity;
use App\Basket\Entities\ApplicationEntity;
use App\Basket\Gateways\ApplicationGateway;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Application Synchronisation Service
 *
 * @author WN
 * @package App\Basket\Synchronisation
 */
class ApplicationSynchronisationService extends AbstractSynchronisationService
{
    private $applicationGateway;

    /**
     * @param ApplicationGateway $applicationGateway
     * @param LoggerInterface|null $logger
     */
    public function __construct(ApplicationGateway $applicationGateway, LoggerInterface $logger = null)
    {
        parent::__construct($logger);
        $this->applicationGateway = $applicationGateway;
    }

    /**
     * @author WN
     * @param $id
     * @return Application
     * @throws \Exception
     */
    public function synchroniseApplication($id)
    {
        $application = $this->fetchApplicationLocalObject($id);
        $installation = $this->fetchInstallationLocalObject($application->installation_id);
        $merchant = $this->fetchMerchantLocalObject($installation->merchant_id);

        try {
            $applicationEntity = $this->applicationGateway->getApplication($application->ext_id, $merchant->token);

        } catch (\Exception $e) {

            $installation->linked = false;
            $installation->save();

            $this->logError('InstallationSynchronisationService failed ' . $e->getMessage());
            throw $e;
        }

        $this->mapApplication($applicationEntity, $application);
        $application->save();

        return $application;

    }

    /**
     * @author WN
     * @param ApplicationEntity $applicationEntity
     * @param Application $application
     */
    private function mapApplication(ApplicationEntity $applicationEntity, Application $application)
    {
        $application->ext_current_status = $applicationEntity->getCurrentStatus();

        $this->mapOrder($application, $applicationEntity->getOrder());

        if ($applicationEntity->getProducts()) {
            $application->ext_products_options = json_encode($applicationEntity->getProducts()->getOptions());
            $application->ext_products_groups = $applicationEntity->getProducts()->getGroup();
            $application->ext_products_default = $applicationEntity->getProducts()->getDefault();
        }

        if ($applicationEntity->getFulfilment()) {
            $application->ext_fulfilment_method = $applicationEntity->getFulfilment()->getMethod();
            $application->ext_fulfilment_location = $applicationEntity->getFulfilment()->getLocation();
        }

        $this->mapCustomer($application, $applicationEntity->getCustomer());
        $this->mapApplicationAddress($application, $applicationEntity->getApplicationAddress());
        $this->mapApplicant($application, $applicationEntity->getApplicant());

        $application->ext_metadata = json_encode($applicationEntity->getMetadata());
    }

    /**
     * @param Application $application
     * @param OrderEntity $orderEntity
     */
    private function mapOrder(Application $application, OrderEntity $orderEntity)
    {
        $application->ext_order_reference = $orderEntity->getReference();
        $application->ext_order_amount = $orderEntity->getAmount();
        $application->ext_order_description = $orderEntity->getDescription();
        $application->ext_order_validity = $orderEntity->getValidity();
    }

    /**
     * @author WN
     * @param CustomerEntity $customerEntity
     * @param Application $application
     */
    private function mapCustomer(Application $application, CustomerEntity $customerEntity = null)
    {
        if ($customerEntity !== null) {
            $application->ext_customer_title = $customerEntity->getTitle();
            $application->ext_customer_first_name = $customerEntity->getFirstName();
            $application->ext_customer_last_name = $customerEntity->getLastName();
            $application->ext_customer_email_address = $customerEntity->getEmailAddress();
            $application->ext_customer_phone_home = $customerEntity->getPhoneHome();
            $application->ext_customer_phone_mobile = $customerEntity->getPhoneMobile();
            $application->ext_customer_postcode = $customerEntity->getPostcode();
        }
    }

    /**
     * @author WN
     * @param AddressEntity $addressEntity
     * @param Application $application
     */
    private function mapApplicationAddress(Application $application, AddressEntity $addressEntity = null)
    {
        if ($addressEntity !== null) {
            $application->ext_application_address_abode = $addressEntity->getAbode();
            $application->ext_application_address_building_name = $addressEntity->getBuildingName();
            $application->ext_application_address_building_number = $addressEntity->getBuildingNumber();
            $application->ext_application_address_street = $addressEntity->getStreet();
            $application->ext_application_address_locality = $addressEntity->getLocality();
            $application->ext_application_address_town = $addressEntity->getTown();
            $application->ext_application_address_postcode = $addressEntity->getPostcode();
        }
    }

    /**
     * @author WN
     * @param ApplicantEntity $applicantEntity
     * @param Application $application
     */
    private function mapApplicant(Application $application, ApplicantEntity $applicantEntity = null)
    {
        if ($applicantEntity !== null) {
            $application->ext_applicant_title = $applicantEntity->getTitle();
            $application->ext_applicant_first_name = $applicantEntity->getFirstName();
            $application->ext_applicant_last_name = $applicantEntity->getLastName();
            $application->ext_applicant_date_of_birth = $applicantEntity->getDateOfBirth();
            $application->ext_applicant_email_address = $applicantEntity->getEmailAddress();
            $application->ext_applicant_phone_home = $applicantEntity->getPhoneHome();
            $application->ext_applicant_phone_mobile = $applicantEntity->getPhoneMobile();
            $application->ext_applicant_postcode = $applicantEntity->getEmailAddress();
        }
    }

    /**
     * @param $id
     * @return Application
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    private function fetchApplicationLocalObject($id)
    {
        try {
            return Application::findOrFail($id);

        } catch (ModelNotFoundException $e) {
            $this->logError(
                __CLASS__ . ': Failed fetching Installation[' . $id . '] local object: ' . $e->getMessage()
            );
            throw $e;
        }
    }
}
