<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Basket\Entities;

use PayBreak\Sdk\Entities\MerchantEntity;

/**
 * Merchant Entity Test
 *
 * @author WN
 * @package Tests\Basket\Entities
 */
class MerchantEntityTest extends \TestCase
{
    public function testInstance()
    {
        $entity = new MerchantEntity();

        $this->assertInstanceOf('WNowicki\Generic\Contracts\Entity', $entity);
        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $entity);
    }

    public function testMakeEmpty()
    {
        $this->assertInstanceOf('WNowicki\Generic\Contracts\Entity', MerchantEntity::make([]));
        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', MerchantEntity::make([]));
    }

    public function testToArray()
    {
        $this->assertInternalType('array', MerchantEntity::make([])->toArray());
    }

    public function testSetId()
    {
        $entity = new MerchantEntity();

        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $entity->setId(2));
    }

    public function testGetId()
    {
        $entity = new MerchantEntity();

        $entity->setId(2);

        $this->assertSame(2, $entity->getId());
    }

    public function testSetCompanyName()
    {
        $entity = new MerchantEntity();

        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $entity->setCompanyName('Test Name'));
    }

    public function testGetCompanyName()
    {
        $entity = new MerchantEntity();

        $entity->setCompanyName('Test Name');

        $this->assertSame('Test Name', $entity->getCompanyName());
    }

    public function testSetAddress()
    {
        $entity = new MerchantEntity();

        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $entity->setAddress('Address'));
    }

    public function testGetAddress()
    {
        $entity = new MerchantEntity();

        $entity->setAddress('Address');

        $this->assertSame('Address', $entity->getAddress());
    }

    public function testSetProcessingDays()
    {
        $entity = new MerchantEntity();

        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $entity->setProcessingDays(2));
    }

    public function testGetProcessingDays()
    {
        $entity = new MerchantEntity();

        $entity->setProcessingDays(2);

        $this->assertSame(2, $entity->getProcessingDays());
    }

    public function testSetMinimumAmountSettled()
    {
        $entity = new MerchantEntity();

        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $entity->setMinimumAmountSettled(2));
    }

    public function testGetMinimumAmountSettled()
    {
        $entity = new MerchantEntity();

        $entity->setMinimumAmountSettled(2);

        $this->assertSame(2, $entity->getMinimumAmountSettled());
    }

    public function testSetAddressOnAgreement()
    {
        $entity = new MerchantEntity();

        $this->assertInstanceOf('PayBreak\Sdk\Entities\MerchantEntity', $entity->setAddressOnAgreements(2));
    }

    public function testGetAddressOnAgreement()
    {
        $entity = new MerchantEntity();

        $entity->setAddressOnAgreements(2);

        $this->assertSame(2, $entity->getAddressOnAgreements());
    }

    public function testToArrayData()
    {
        $properties = [
            'id' => 45,
            'company_name' => 'Test Name',
            'address' => 'Address',
            'processing_days' => 4,
            'minimum_amount_settled' => 1000,
            'address_on_agreements' => 'Address on Agreement',
        ];

        $this->assertSame($properties, MerchantEntity::make($properties)->toArray());
    }
}
