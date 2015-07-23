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

use App\Basket\Entities\MerchantEntity;
use App\Basket\Gateways\MerchantGateway;
use Psr\Log\LoggerInterface;
use App\Basket\Merchant;

/**
 * Merchant Synchronisation Service
 *
 * @author WN
 * @package App\Basket\Synchronisation
 */
class MerchantSynchronisationService extends AbstractSynchronisationService
{
    private $gateway;
    private $installationSynchronisationService;

    /**
     * @author WN
     * @param MerchantGateway $gateway
     * @param InstallationSynchronisationService $installationSynchronisationService
     * @param LoggerInterface $logger
     */
    public function __construct(
        MerchantGateway $gateway,
        InstallationSynchronisationService $installationSynchronisationService,
        LoggerInterface $logger = null
    ) {
        $this->gateway = $gateway;
        $this->installationSynchronisationService = $installationSynchronisationService;
        parent::__construct($logger);
    }

    /**
     * @author WN
     * @param int $id
     * @param bool $withInstallations
     * @return Merchant
     * @throws \Exception
     */
    public function synchroniseMerchant($id, $withInstallations = false)
    {
        $merchant = $this->fetchMerchantLocalObject($id);

        try {
            $merchantEntity = $this->gateway->getMerchant($id, $merchant->token);

        } catch (\Exception $e) {

            $merchant->linked = false;
            $merchant->save();

            $this->logError('MerchantSynchronisationService failed ' . $e->getMessage());
            throw $e;
        }

        $this->mapMerchant($merchantEntity, $merchant);
        $merchant->linked = true;
        $merchant->save();

        if ($withInstallations) {
            $this->installationSynchronisationService->synchroniseAllInstallations($merchant->id);
        }

        return $merchant;
    }

    /**
     * @author WN
     * @param MerchantEntity $externalEntity
     * @param Merchant $internalData
     */
    private function mapMerchant(MerchantEntity $externalEntity, Merchant $internalData)
    {
        $internalData->ext_company_name = $externalEntity->getCompanyName();
        $internalData->ext_address = $externalEntity->getAddress();
        $internalData->ext_processing_days = $externalEntity->getProcessingDays();
        $internalData->ext_minimum_amount_settled = $externalEntity->getMinimumAmountSettled();
        $internalData->ext_address_on_agreements = $externalEntity->getAddressOnAgreements();
    }
}
