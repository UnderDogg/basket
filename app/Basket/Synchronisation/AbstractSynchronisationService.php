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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AbstractSynchronisationService {

    private $logger;

    public function __construct(LoggerInterface $logger = [])
    {
        $this->logger = $logger;
    }

    /**
     * @param int $id
     * @return Merchant
     */
    protected function fetchLocalObject($id)
    {
        try {

            return Merchant::findOrFail($id);

        } catch (ModelNotFoundException $e) {
            $this->logError('MerchantSynchronisationService: Failed fetching local object: ' . $e->getMessage());

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
