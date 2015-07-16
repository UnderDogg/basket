<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Gateways;

use App\Gateways\ApiClientFactory;
use Psr\Log\LoggerInterface;
use WNowicki\Generic\Logger\PsrLoggerTrait;

/**
 * Abstract Gateway
 *
 * @author WN
 * @package App\Basket\Gateways
 */
abstract class AbstractGateway
{
    use PsrLoggerTrait;

    private $apiFactory;
    private $logger;

    /**
     * @author WN
     * @param ApiClientFactory $factory
     * @param LoggerInterface $logger
     */
    public function __construct(ApiClientFactory $factory, LoggerInterface $logger = null)
    {
        $this->apiFactory = $factory;
        $this->logger = $logger;
    }

    /**
     * @author WN
     * @return ApiClientFactory
     */
    public function getApiFactory()
    {
        return $this->apiFactory;
    }

    /**
     * @author WN
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
