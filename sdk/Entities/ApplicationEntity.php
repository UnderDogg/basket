<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PayBreak\Sdk\Entities;

use WNowicki\Generic\AbstractEntity;

/**
 * Application Entity
 *
 * @author WN
 * @method $this setId(int $id)
 * @method int|null getId()
 * @method $this setPostedDate(string $postedDate)
 * @method string|null getPostedDate()
 * @method $this setCurrentStatus(string $currentStatus)
 * @method string|null getCurrentStatus()
 * @method $this setCustomer(Application\CustomerEntity $customer)
 * @method Application\CustomerEntity|null getCustomer()
 * @method $this setApplicationAddress(Application\AddressEntity $applicationAddress)
 * @method Application\AddressEntity|null getApplicationAddress()
 * @method $this setInstallation(string $installation)
 * @method string|null getInstallation()
 * @method $this setOrder(Application\OrderEntity $order)
 * @method Application\OrderEntity|null getOrder()
 * @method $this setProducts(Application\ProductsEntity $products)
 * @method Application\ProductsEntity|null getProducts()
 * @method $this setFulfilment(Application\FulfilmentEntity $fulfilment)
 * @method Application\FulfilmentEntity|null getFulfilment()
 * @method $this setApplicant(Application\ApplicantEntity $applicant)
 * @method Application\ApplicantEntity|null getApplicant()
 * @method $this setFinance(Application\FinanceEntity $finance)
 * @method Application\FinanceEntity|null getFinance()
 * @method $this setMetadata(array $metadata)
 * @method array|null getMetadata()
 * @method $this setResumeUrl(string $resumeUrl)
 * @method string|null getResumeUrl()
 * @package PayBreak\Sdk\Entities
 */
class ApplicationEntity extends AbstractEntity
{
    protected $properties = [
        'id' => self::TYPE_INT,
        'posted_date' => self::TYPE_STRING,
        'current_status' => self::TYPE_STRING,
        'customer' => 'PayBreak\Sdk\Entities\Application\CustomerEntity',
        'application_address' => 'PayBreak\Sdk\Entities\Application\AddressEntity',
        'installation' => self::TYPE_STRING,
        'order' => 'PayBreak\Sdk\Entities\Application\OrderEntity',
        'products' => 'PayBreak\Sdk\Entities\Application\ProductsEntity',
        'fulfilment' => 'PayBreak\Sdk\Entities\Application\FulfilmentEntity',
        'applicant' => 'PayBreak\Sdk\Entities\Application\ApplicantEntity',
        'finance' => 'PayBreak\Sdk\Entities\Application\FinanceEntity',
        'metadata' => self::TYPE_ARRAY,
        'resume_url' => self::TYPE_STRING,
    ];
}
