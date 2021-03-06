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
use App\Basket\Location;
use App\Exceptions\Exception;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use PayBreak\Sdk\Entities\ApplicationEntity;
use PayBreak\Sdk\Entities\Application\ApplicantEntity;
use PayBreak\Sdk\Entities\Application\OrderEntity;
use PayBreak\Sdk\Entities\Application\ProductsEntity;
use PayBreak\Sdk\Gateways\ApplicationGateway;
use PayBreak\Sdk\Gateways\PartialRefundGateway;
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
    private $partialRefundGateway;

    /**
     * @param ApplicationGateway $applicationGateway
     * @param PartialRefundGateway $partialRefundGateway
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        ApplicationGateway $applicationGateway,
        PartialRefundGateway $partialRefundGateway,
        LoggerInterface $logger = null
    ) {
        parent::__construct($logger);

        $this->applicationGateway = $applicationGateway;
        $this->partialRefundGateway = $partialRefundGateway;
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

        $mapApplicationHelper = new MapApplicationHelper();
        $mapApplicationHelper->mapApplication($applicationEntity, $application);
        $application->save();

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
     * @param string $reference
     * @return bool
     * @throws \Exception
     */
    public function fulfil($id, $reference = null)
    {
        $application = $this->fetchApplicationLocalObject($id);
        $merchant = $this->fetchMerchantLocalObject($application->installation->merchant_id);

        try {
            return $this->applicationGateway->fulfilApplication($application->ext_id, $merchant->token, $reference);
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
     * @param int $id
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
            return $this->partialRefundGateway->requestPartialRefund(
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
     * @param Location $location
     * @param OrderEntity $orderEntity
     * @param ProductsEntity $productsEntity
     * @param ApplicantEntity $applicantEntity
     * @param User $requester
     * @return Application
     * @throws Exception
     */
    public function initialiseApplication(
        Location $location,
        OrderEntity $orderEntity,
        ProductsEntity $productsEntity,
        ApplicantEntity $applicantEntity,
        User $requester
    ) {
        $applicationParams = [
            'installation' => $location->installation->ext_id,
            'order' => $orderEntity->toArray(),
            'products' => $productsEntity->toArray(true),
            'fulfilment' => [
                'method' => 'collection',
                'location' => $location->reference,
            ],
            'applicant' => $applicantEntity->toArray(),
        ];

        $application = ApplicationEntity::make($applicationParams);

        $this->logInfo(
            'IniApp: Application reference[' . $orderEntity->getReference() . '] ready to be initialised',
            ['application' => $application->toArray()]
        );

        try {
            $newApplication = $this->applicationGateway->initialiseApplication(
                $application,
                $location->installation->merchant->token
            );

            $this->logInfo(
                'IniApp: Application reference[' . $orderEntity->getReference() . ']
                successfully initialised at provider with ID[' . $newApplication->getId() . ']'
            );

            $app = $this->createNewLocal($newApplication, $location->installation->id, $requester->id, $location->id);

            $this->logInfo(
                'IniApp: Application reference[' . $orderEntity->getReference() . ']
                successfully stored in the local system'
            );

            return $app;
        } catch (\Exception $e) {
            $this->logError('IniApp: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @author SL
     * @param Application $application
     * @param array $filterParams
     * @return array
     * @throws Exception
     */
    public function getRemoteMerchantPayments(Application $application, array $filterParams = [])
    {
        $merchant = $this->fetchMerchantLocalObject($application->installation->merchant_id);

        try {
            $merchantPayments = $this->applicationGateway->getMerchantPayments(
                $application->ext_id,
                $merchant->token,
                $filterParams
            );

            return $merchantPayments;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @author SL
     *
     * @param Application $application
     * @param Carbon $effectiveDate
     * @param int $amount
     * @return bool
     * @throws Exception
     */
    public function addRemoteMerchantPayment(Application $application, Carbon $effectiveDate, $amount)
    {
        try {
            $merchant = $this->fetchMerchantLocalObject($application->installation->merchant_id);

            $status = $this->applicationGateway->addMerchantPayment(
                $application->ext_id,
                $effectiveDate,
                $amount,
                $merchant->token
            );

            return empty($status);
        } catch (\Exception $e) {
            $this->logError('AddRemoteMerchantPayment: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @author WN
     * @param ApplicationEntity $applicationEntity
     * @param int $installationId
     * @param int|null $requester
     * @param int|null $location
     * @return Application
     * @throws Exception
     */
    private function createNewLocal(
        ApplicationEntity $applicationEntity,
        $installationId,
        $requester = null,
        $location = null
    ) {
        $app = new Application();
        $app->installation_id = $installationId;
        $app->ext_id = $applicationEntity->getId();
        $app->user_id = $requester;
        $app->location_id = $location;

        $mapApplicationHelper = new MapApplicationHelper();
        $mapApplicationHelper->mapApplication($applicationEntity, $app);

        if ($app->save()) {
            ApplicationEventHelper::addEvent($app, ApplicationEvent::TYPE_NOTIFICATION_INITIALISED, Auth::user());
            return $app;
        }

        throw new Exception('Problem saving Application');
    }

    /**
     * @param int $id
     * @return Application
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    private function fetchApplicationLocalObject($id)
    {
        try {
            /** @var Application $application */
            $application = Application::findOrFail($id);
            return $application;
        } catch (ModelNotFoundException $e) {
            $this->logError(
                __CLASS__ . ': Failed fetching Installation[' . $id . '] local object: ' . $e->getMessage()
            );
            throw $e;
        }
    }

    /**
     * @author EB
     * @param int $applicationId
     * @return array
     * @throws \Exception
     */
    public function getCreditInfoForApplication($applicationId)
    {
        $application = $this->fetchApplicationLocalObject($applicationId);

        try {
            return $this->applicationGateway->getApplicationCreditInfo(
                $application->installation->ext_id,
                $application->ext_id,
                $application->installation->merchant->token
            );
        } catch (\Exception $e) {
            $this->logError(
                'ApplicationSynchronisationService: Fetching Application Credit Information failed: ' . $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * @author SL
     * @param Application $application
     * @return array
     * @throws \Exception
     */
    public function getApplicationHistory(Application $application)
    {
        try {
            return $this->applicationGateway->getApplicationHistory(
                $application->ext_id,
                $application->installation->merchant->token
            );
        } catch (\Exception $e) {
            $this->logError(
                'ApplicationSynchronisationService: Fetching Application Status History failed: ' . $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * @author WN, EB
     * @param string $email
     * @param Location $location
     * @param OrderEntity $orderEntity
     * @param ProductsEntity $productsEntity
     * @param ApplicantEntity $applicantEntity
     * @param User $requester
     * @return Application
     * @throws Exception
     */
    public function initialiseAssistedApplication(
        $email,
        Location $location,
        OrderEntity $orderEntity,
        ProductsEntity $productsEntity,
        ApplicantEntity $applicantEntity,
        User $requester
    ) {
        $applicationParams = [
            'email' => $email,
            'installation' => $location->installation->ext_id,
            'order' => $orderEntity->toArray(),
            'products' => $productsEntity->toArray(true),
            'fulfilment' => [
                'method' => 'collection',
                'location' => $location->reference,
            ],
            'applicant' => $applicantEntity->toArray(),
        ];


        $application = ApplicationEntity::make($applicationParams);

        $this->logInfo(
            'IniAssistedApp: Application reference[' . $orderEntity->getReference() . '] ready to be initialised',
            ['application' => $application->toArray()]
        );

        try {
            $newApplication = $this->applicationGateway->initialiseAssistedApplication(
                $application,
                $location->installation->merchant->token
            );

            $this->logInfo(
                'IniAssistedApp: Application reference[' . $orderEntity->getReference() . ']
                successfully initialised at provider with ID[' . $newApplication->getId() . ']'
            );

            $app = $this->createNewLocal($newApplication, $location->installation->id, $requester->id, $location->id);

            $this->logInfo(
                'IniAssistedApp: Application reference[' . $orderEntity->getReference() . ']
                successfully stored in the local system'
            );

            return $app;
        } catch (\Exception $e) {
            $this->logError('IniAssistedApp: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
