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
use Illuminate\Database\Eloquent\Collection;
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
     * @return array
     * @throws \Exception
     */
    public function synchroniseAllInstallations($merchantId)
    {
        $rtn = [];
        $merchant = $this->fetchMerchantLocalObject($merchantId);
        $localInstallations = Installation::where('merchant_id', '=', $merchantId)->get();

        try {

            $externalInstallations = $this->installationGateway->getInstallations($merchant->token);

        } catch (\Exception $e) {

            $this->logError(
                'InstallationSynchronisationService failed while fetching for Merchant[' . $merchantId . ']:' .
                $e->getMessage()
            );
            throw $e;

        }

        $rtn['new'] = $this->synchroniseNewInstallations($externalInstallations, $localInstallations, $merchantId);
        $rtn['unlinked'] = $this->unlinkRestInstallations($localInstallations);

        return $rtn;
    }

    /**
     * @param InstallationEntity[] $externalInstallations
     * @param Collection $localInstallations
     * @param $merchantId
     * @return Installation[]
     */
    private function synchroniseNewInstallations(array $externalInstallations, Collection $localInstallations, $merchantId)
    {
        $rtn = [];

        foreach ($externalInstallations as $installation) {

            if ($this->isNewInstallation($installation->getId(), $localInstallations)) {
                $newInstallation = new Installation();
                $newInstallation->name = $installation->getName();
                $newInstallation->merchant_id = $merchantId;
                $newInstallation->active = false;
                $newInstallation->linked = false;
                $newInstallation->ext_id = $installation->getId();
                $newInstallation->save();

                try {
                    $this->synchroniseInstallation($newInstallation->id);
                } catch (\Exception $e) {
                    // Empty
                }

                $rtn[] = 'New installation ' . $installation->getName() . ' has been added.';
            }
        }

        return $rtn;
    }

    /**
     * @author WN
     * @param Collection $localInstallations
     * @return array
     */
    private function unlinkRestInstallations(Collection $localInstallations)
    {
        $rtn = [];

        foreach ($localInstallations as $installation) {

            if ($installation->linked) {
                $installation->linked = false;
                $installation->save();

                $rtn[] = 'Installation ' . $installation->name . ' has been unlinked';
            }
        }

        return $rtn;
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
     * @param Collection $installations
     * @return bool
     */
    private function isNewInstallation($externalId, Collection $installations)
    {
        $item = $installations->search(function($item, $key) use ($externalId, $installations) {
            if ($item->ext_id == $externalId) {
                return true;
            }
            return false;
        });

        if ($item !== false) {

            $installations->forget($item);
            return false;
        }

        return true;
    }
}
