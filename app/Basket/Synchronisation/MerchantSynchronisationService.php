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
use App\Basket\Merchant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Log\LoggerInterface;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Merchant Synchronisation Service
 *
 * @author WN
 * @package App\Basket\Synchronisation
 */
class MerchantSynchronisationService
{
    use PsrLoggerTrait;

    private $gateway;
    private $logger;

    public function __construct(MerchantGateway $gateway, LoggerInterface $logger = null)
    {
        $this->gateway = $gateway;
        $this->logger = $logger;
    }

    /**
     * @author WN
     * @param int $id
     * @return Merchant
     * @throws \Exception
     */
    public function synchroniseMerchant($id)
    {
        $merchant = $this->fetchLocalObject($id);

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

        return $merchant;
    }

    /**
     * @param int $id
     * @return Merchant
     */
    private function fetchLocalObject($id)
    {
        try {

            return Merchant::findOrFail($id);

        } catch (ModelNotFoundException $e) {
            $this->logError('MerchantSynchronisationService: Failed fetching local object: ' . $e->getMessage());

            throw $e;
        }
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

    /**
     * @return LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
