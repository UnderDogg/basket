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
use App\Basket\ApplicationEvent;
use App\Basket\ApplicationEvent\ApplicationEventHelper;
use App\Exceptions\Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use PayBreak\Sdk\Entities\ApplicationEntity;
use PayBreak\Sdk\Entities\Application\AddressEntity;
use PayBreak\Sdk\Entities\Application\ApplicantEntity;
use PayBreak\Sdk\Entities\Application\CancellationEntity;
use PayBreak\Sdk\Entities\Application\CustomerEntity;
use PayBreak\Sdk\Entities\Application\FinanceEntity;
use PayBreak\Sdk\Entities\Application\OrderEntity;
use PayBreak\Sdk\Gateways\ApplicationGateway;
use Psr\Log\LoggerInterface;

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
     * @param int $id Internal application ID
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
            $this->logError('ApplicationSynchronisationService failed ' . $e->getMessage());
            throw $e;
        }

        $this->mapApplication($applicationEntity, $application);
        $application->save();
        ApplicationEventHelper::addEvent($application, ApplicationEvent::TYPE_NOTIFICATION_INITIALISED, Auth::user());

        return $application;
    }

    /**
     * @author WN
     * @param int $applicationId External Application ID
     * @param string $installationId External Installation ID
     * @return Application
     * @throws \Exception
     */
    public function linkApplication($applicationId, $installationId)
    {
        try {
            return $this->fetchApplicationByExternalId($applicationId);
        } catch (ModelNotFoundException $e) {
            // nothing to do
        }

        try {
            $installation = $this->fetchInstallationByExternalId($installationId);
        } catch (\Exception $e) {

            $this->logError('linkApplication: Installation not found for ID[' . $installationId . ']');
            throw new Exception('Installation not found');
        }

        try {
            $applicationEntity = $this->applicationGateway
                ->getApplication($applicationId, $installation->merchant->token);

        } catch (\Exception $e) {
            $this->logError('Link Application failed ' . $e->getMessage());
            throw $e;
        }

        try {
            return $this->createNewLocal($applicationEntity, $installation->id);
        } catch (\Exception $e) {

            $this->logError('LinkApplication: Problem saving Application[' . $installationId . ']');
            throw $e;
        }
    }

    /**
     * @author WN
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function fulfil($id)
    {
        $application = $this->fetchApplicationLocalObject($id);
        $merchant = $this->fetchMerchantLocalObject($application->installation->merchant_id);

        try {
            return $this->applicationGateway->fulfilApplication($application->ext_id, $merchant->token);

        } catch (\Exception $e) {

            $this->logError('ApplicationSynchronisationService: Fulfilment failed ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @author WN
     * @param int $id
     * @param $description
     * @return bool
     * @throws \Exception
     */
    public function requestCancellation($id, $description)
    {
        $application = $this->fetchApplicationLocalObject($id);
        $merchant = $this->fetchMerchantLocalObject($application->installation->merchant_id);

        try {
            return $this->applicationGateway->cancelApplication($application->ext_id, $description, $merchant->token);

        } catch (\Exception $e) {

            $this->logError('ApplicationSynchronisationService: CancellationRequest failed ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @author LH
     * @param $id
     * @param $refundAmount
     * @param $effectiveDate
     * @param $description
     * @return mixed
     * @throws \Exception
     */
    public function requestPartialRefund($id, $refundAmount, $effectiveDate, $description)
    {
        $application = $this->fetchApplicationLocalObject($id);
        $merchant = $this->fetchMerchantLocalObject($application->installation->merchant_id);

        try {

            $partialRefundGateway = \App::make('\PayBreak\Sdk\Gateways\PartialRefundGateway');

            return $partialRefundGateway->requestPartialRefund(
                $merchant->token,
                $application->ext_id,
                $refundAmount,
                $effectiveDate,
                $description
            );

        } catch (\Exception $e) {

            $this->logError('ApplicationSynchronisationService: RequestPartialRefund failed ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @author WN, EB
     * @param int $installationId
     * @param string $reference
     * @param int $amount
     * @param string $description
     * @param string $validity
     * @param string $productGroup
     * @param array $productOptions
     * @param string $location
     * @param int $requester
     * @param ApplicantEntity $applicantEntity
     * @param int|null $deposit
     * @return Application
     * @throws Exception
     * @internal param null $deposit
     */
    public function initialiseApplication(
        $installationId,
        $reference,
        $amount,
        $description,
        $validity,
        $productGroup,
        array $productOptions,
        $location,
        $requester,
        ApplicantEntity $applicantEntity,
        $deposit = null
    ) {
        $installation = $this->fetchInstallationLocalObject($installationId);

        $applicationParams = [
            'installation' => $installation->ext_id,
            'order' => [
                'reference' => $reference,
                'amount' => (int) $amount,
                'description' => $description,
                'validity' => Carbon::now()->addSeconds($validity)->toDateTimeString(),
                'deposit_amount' => $deposit,
            ],
            'products' => [
                'group' => $productGroup,
                'options' => $productOptions,
            ],
            'fulfilment' => [
                'method' => 'collection',
                'location' => $location->reference,
            ],
            'applicant' => $applicantEntity->toArray(),
        ];

        $application = ApplicationEntity::make($applicationParams);

        $this->logInfo(
            'IniApp: Application reference[' . $reference . '] ready to be initialised',
            ['application' => $application->toArray()]
        );

        try {
            $newApplication = $this->applicationGateway->initialiseApplication(
                $application,
                $installation->merchant->token
            );

            $this->logInfo(
                'IniApp: Application reference[' . $reference . '] successfully initialised at provider with ID[' .
                $newApplication->getId() . ']'
            );

            $app = $this->createNewLocal($newApplication, $installation->id, $requester, $location->id);

            $this->logInfo('IniApp: Application reference[' . $reference . '] successfully stored in a local system');

            return $app;

        } catch (\Exception $e) {

            $this->logError('IniApp: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @author WN
     * @param ApplicationEntity $applicationEntity
     * @param int $installationId
     * @param int|null $requester
     * @param null $location
     * @return Application
     * @throws Exception
     */
    private function createNewLocal(
        ApplicationEntity $applicationEntity,
        $installationId,
        $requester = null,
        $location = null
    )
    {
        $app = new Application();
        $app->installation_id = $installationId;
        $app->ext_id = $applicationEntity->getId();
        $app->user_id = $requester;
        $app->location_id = $location;
        $app->ext_resume_url = $applicationEntity->getResumeUrl();

        $this->mapApplication($applicationEntity, $app);

        if ($app->save()) {
            ApplicationEventHelper::addEvent($app, ApplicationEvent::TYPE_NOTIFICATION_INITIALISED, Auth::user());
            return $app;
        }

        throw new Exception('Problem saving Application');
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
        $this->mapFinance($application, $applicationEntity->getFinance());
        $this->mapCancellation($application, $applicationEntity->getCancellation());

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
     * @author WN
     * @param Application $application
     * @param FinanceEntity $financeEntity
     */
    private function mapFinance(Application $application, FinanceEntity $financeEntity = null)
    {
        if ($financeEntity !== null) {
            $application->ext_finance_loan_amount = $financeEntity->getLoanAmount();
            $application->ext_finance_order_amount = $financeEntity->getOrderAmount();
            $application->ext_finance_deposit = $financeEntity->getDepositAmount();
            $application->ext_finance_subsidy = $financeEntity->getSubsidyAmount();
            $application->ext_finance_commission = $financeEntity->getCommissionAmount();
            $application->ext_finance_net_settlement = $financeEntity->getSettlementNetAmount();
            $application->ext_finance_option = $financeEntity->getOption();
            $application->ext_finance_option_group = $financeEntity->getOptionGroup();
            $application->ext_finance_holiday = $financeEntity->getHoliday();
            $application->ext_finance_payments = $financeEntity->getPayments();
            $application->ext_finance_term = $financeEntity->getTerm();
        }
    }

    /**
     * @author WN
     * @param Application $application
     * @param CancellationEntity|null $cancellationEntity
     */
    private function mapCancellation(Application $application, CancellationEntity $cancellationEntity = null)
    {
        if ($cancellationEntity !== null) {
            $application->ext_cancellation_requested = $cancellationEntity->getRequested();
            $application->ext_cancellation_effective_date = Carbon::parse($cancellationEntity->getEffectiveDate());
            $application->ext_cancellation_requested_date = Carbon::parse($cancellationEntity->getRequestedDate());
            $application->ext_cancellation_description = $cancellationEntity->getDescription();
            $application->ext_cancellation_fee_amount = $cancellationEntity->getFeeAmount();
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
