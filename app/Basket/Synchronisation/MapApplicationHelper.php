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
use Carbon\Carbon;
use PayBreak\Sdk\Entities\Application\AddressEntity;
use PayBreak\Sdk\Entities\Application\ApplicantEntity;
use PayBreak\Sdk\Entities\Application\CancellationEntity;
use PayBreak\Sdk\Entities\Application\CustomerEntity;
use PayBreak\Sdk\Entities\Application\FinanceEntity;
use PayBreak\Sdk\Entities\Application\FulfilmentEntity;
use PayBreak\Sdk\Entities\Application\OrderEntity;
use PayBreak\Sdk\Entities\Application\ProductsEntity;
use PayBreak\Sdk\Entities\ApplicationEntity;

/**
 * Map Application Helper
 *
 * @author EB
 * @package App\Basket\Synchronisation
 */
class MapApplicationHelper
{
    /**
     * @author WN
     * @param ApplicationEntity $applicationEntity
     * @param Application $application
     */
    public function mapApplication(ApplicationEntity $applicationEntity, Application $application)
    {
        $application->ext_current_status = $applicationEntity->getCurrentStatus();

        $this->mapOrder($application, $applicationEntity->getOrder());
        $this->mapProducts($application, $applicationEntity->getProducts());
        $this->mapFulfilment($application, $applicationEntity->getFulfilment());
        $this->mapCustomer($application, $applicationEntity->getCustomer());
        $this->mapApplicationAddress($application, $applicationEntity->getApplicationAddress());
        $this->mapApplicant($application, $applicationEntity->getApplicant());
        $this->mapFinance($application, $applicationEntity->getFinance());
        $this->mapCancellation($application, $applicationEntity->getCancellation());

        $application->ext_resume_url = $applicationEntity->getResumeUrl();
        $application->ext_metadata = json_encode($applicationEntity->getMetadata());
        $application->ext_user = $applicationEntity->getUser();
        $application->ext_is_regulated = $applicationEntity->getIsRegulated();
    }

    /**
     * @author WN
     * @param Application $application
     * @param OrderEntity $orderEntity
     */
    private function mapOrder(Application $application, OrderEntity $orderEntity = null)
    {
        if ($orderEntity !== null) {
            $application->ext_order_reference = $orderEntity->getReference();
            $application->ext_order_amount = $orderEntity->getAmount();
            $application->ext_order_description = $orderEntity->getDescription();
            $application->ext_order_validity = $orderEntity->getValidity();
            $application->ext_order_hold = $orderEntity->getHold();
        }
    }

    /**
     * @author EB
     * @param Application $application
     * @param ProductsEntity $productsEntity
     */
    private function mapProducts(Application $application, ProductsEntity $productsEntity = null)
    {
        if ($productsEntity !== null) {
            $application->ext_products_options = json_encode($productsEntity->getOptions());
            $application->ext_products_groups = $productsEntity->getGroup();
            $application->ext_products_default = $productsEntity->getDefault();
        }
    }

    /**
     * @author EB
     * @param Application $application
     * @param FulfilmentEntity $fulfilmentEntity
     */
    private function mapFulfilment(Application $application, FulfilmentEntity $fulfilmentEntity = null)
    {
        if ($fulfilmentEntity !== null) {
            $application->ext_fulfilment_method = $fulfilmentEntity->getMethod();
            $application->ext_fulfilment_location = $fulfilmentEntity->getLocation();
            $application->ext_fulfilment_reference = $fulfilmentEntity->getReference();
        }
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
            $application->ext_applicant_postcode = $applicantEntity->getPostcode();
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
}
