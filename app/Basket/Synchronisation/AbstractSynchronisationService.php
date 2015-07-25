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

use Illuminate\Database\Eloquent\Model;
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
        return $this->fetchLocalObject((new Merchant()), $id, 'merchant');
    }

    /**
     * @param $id
     * @return Installation
     */
    protected function fetchInstallationLocalObject($id)
    {
        return $this->fetchLocalObject((new Installation()), $id, 'installation');
    }

    /**
     * @author WN
     * @param Model $model
     * @param $id
     * @param $modelName
     * @return Model
     */
    private function fetchLocalObject(Model $model, $id, $modelName)
    {
        try {
            return $model->findOrFail($id);

        } catch (ModelNotFoundException $e) {
            $this->logError(
                __CLASS__ . ': Failed fetching ' . ucwords($modelName) .
                '[' . $id . '] local object: ' . $e->getMessage()
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
