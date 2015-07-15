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

    public function __construct(MerchantGateway $gateway, LoggerInterface $logger)
    {
        $this->gateway = $gateway;
        $this->logger = $logger;
    }

    /**
     * @author WN
     * @param $id
     * @throws \Exception
     */
    public function synchroniseMerchant($id)
    {
        try {
            /** @var Merchant $merchant */
            $merchant = Merchant::findOrFail($id);

            $merchantEntity = $this->gateway->getMerchant($id, $merchant->token);

            $this->mapMerchant($merchantEntity, $merchant);

            $merchant->linked = true;

            $merchant->save();

        } catch (\Exception $e) {

            $this->logError('MerchantSynchronisationService failed ' . $e->getMessage());

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
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
