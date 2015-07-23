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

use Psr\Log\LoggerInterface;
use App\Basket\Merchant;
use App\Basket\Installation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Abstract Synchronisation Service
 *
 * @author WN
 * @package App\Basket\Synchronisation
 */
class AbstractSynchronisationService {

    use PsrLoggerTrait;

    private $logger;

    /**
     * @author WN
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @author WN
     * @param int $id
     * @return Merchant
     */
    protected function fetchMerchantLocalObject($id)
    {
        try {
            return Merchant::findOrFail($id);

        } catch (ModelNotFoundException $e) {
            $this->logError(__CLASS__ . ': Failed fetching Merchant[' . $id . '] local object: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $id
     * @return Installation
     */
    protected function fetchInstallationLocalObject($id)
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
     * @return LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
