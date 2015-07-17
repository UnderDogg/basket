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

use App\Basket\Entities\InstallationEntity;
use App\Basket\Gateways\InstallationGateway;
use App\Basket\Installation;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class InstallationSynchronisationService
 *
 * @author WN
 * @package App\Basket\Synchronisation
 */
class InstallationSynchronisationService extends AbstractSynchronisationService
{
    private $installationGateway;

    /**
     * @param InstallationGateway $installationGateway
     * @param LoggerInterface $logger
     */
    public function __construct(InstallationGateway $installationGateway, LoggerInterface $logger = null)
    {
        $this->installationGateway = $installationGateway;

        parent::__construct($logger);
    }

    /**
     * @author WN
     * @param int $id Local object ID
     * @return Installation
     * @throws \Exception
     */
    public function synchroniseInstallation($id)
    {
        $installation = $this->fetchInstallationLocalObject($id);

        $merchant = $this->fetchMerchantLocalObject($installation->merchant_id);

        try {
            $installationEntity = $this->installationGateway->getInstallation($installation->ext_id, $merchant->token);
        } catch (\Exception $e) {

            $installation->linked = false;
            $installation->save();

            $this->logError('InstallationSynchronisationService failed ' . $e->getMessage());
            throw $e;
        }

        $this->mapInstallation($installationEntity, $installation);
        $installation->linked = true;
        $installation->save();

        return $installation;
    }

    /**
     * @author WN
     * @param int $merchantId
     * @return bool
     */
    public function synchroniseAllInstallations($merchantId)
    {
        $merchant = $this->fetchMerchantLocalObject($merchantId);

        $localInstallations = Installation::where('merchant_id', '=', $merchantId)->get();

        try {

            $externalInstallations = $this->installationGateway->getInstallations($merchant->token);

        } catch (\Exception $e) {

            // TODO Refactor !!!
            die($e->getMessage());

        }

        foreach ($externalInstallations as $installation) {

            if ($this->isNewInstallation($installation->getId(), $localInstallations)) {

                $newInstallation = Installation::create([
                    'name'          => $installation->getName(),
                    'merchant_id'   => $merchantId,
                    'active'        => false,
                    'linked'        => false,
                ]);

                try {
                    $this->synchroniseInstallation($newInstallation->id);
                } catch (\Exception $e) {

                    // TODO
                }
            }
        }

        if (count($localInstallations) > 0) {

            foreach ($localInstallations as $installation) {

                $installation->linked = false;
                $installation->save();
            }
        }

        return true;
    }

    /**
     * @author WN
     * @param InstallationEntity $installationEntity
     * @param Installation $installation
     */
    private function mapInstallation(InstallationEntity $installationEntity, Installation $installation)
    {
        $installation->ext_id = $installationEntity->getId();
        $installation->ext_name = $installationEntity->getName();
        $installation->ext_return_url = $installationEntity->getReturnUrl();
        $installation->ext_notification_url = $installationEntity->getNotificationUrl();
        $installation->ext_default_product = $installationEntity->getDefaultProduct();
    }

    /**
     * @param $id
     * @return Installation
     */
    private function fetchInstallationLocalObject($id)
    {
        try {
            return Installation::findOrFail($id);

        } catch (ModelNotFoundException $e) {
            $this->logError(
                __CLASS__ . ': Failed fetching Installation[' . $id . '] local object: ' . $e->getMessage()
            );
            throw $e;
        }
    }

    /**
     * @author WN
     * @param string $externalId
     * @param array $installations
     * @return bool
     */
    private function isNewInstallation($externalId, array &$installations)
    {
        foreach($installations as &$installation) {

            if ($installation->ext_id == $externalId) {

                unset($installation);
                return false;
            }
        }
        return true;
    }
}
