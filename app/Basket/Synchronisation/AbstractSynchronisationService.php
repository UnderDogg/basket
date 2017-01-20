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
use App\Basket\Installation;
use App\Basket\Merchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Log\LoggerInterface;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Abstract Synchronisation Service
 *
 * @author WN
 * @package App\Basket\Synchronisation
 */
class AbstractSynchronisationService
{
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
     * @param int $id
     * @return Installation
     */
    protected function fetchInstallationLocalObject($id)
    {
        return $this->fetchLocalObject((new Installation()), $id, 'installation');
    }

    /**
     * @author WN
     * @param string $installation
     * @return Installation
     */
    protected function fetchInstallationByExternalId($installation)
    {
        $inst = Installation::where('ext_id', $installation)->get();

        if (count($inst) == 1) {
            return $inst[0];
        }

        throw new ModelNotFoundException('Installation ' . $installation . ' not found.');
    }

    /**
     * @author WN
     * @param int $application
     * @return Application
     */
    protected function fetchApplicationByExternalId($application)
    {
        $app = Application::where('ext_id', $application)->get();

        if (count($app) == 1) {
            return $app[0];
        }

        throw new ModelNotFoundException('Application ' . $application . ' not found.');
    }

    /**
     * @author WN
     * @param Model $model
     * @param int $id
     * @param string $modelName
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
