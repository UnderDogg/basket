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

use App\Basket\Entities\InstallationEntity;

/**
 * Installation Entity Test
 *
 * @author WN
 * @package Tests\Basket\Entities
 */
class InstallationEntityTest extends \TestCase
{
    public function testInstance()
    {
        $entity = new InstallationEntity();

        $this->assertInstanceOf('WNowicki\Generic\EntityInterface', $entity);
        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', $entity);
    }

    public function testMakeEmpty()
    {
        $this->assertInstanceOf('WNowicki\Generic\EntityInterface', InstallationEntity::make([]));
        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', InstallationEntity::make([]));
    }

    public function testToArray()
    {
        $this->assertInternalType('array', InstallationEntity::make([])->toArray());
    }

    public function testSetId()
    {
        $entity = new InstallationEntity();

        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', $entity->setId('Test Name'));
    }

    public function testGetId()
    {
        $entity = new InstallationEntity();

        $entity->setId('Test Name');

        $this->assertSame('Test Name', $entity->getId());
    }

    public function testSetName()
    {
        $entity = new InstallationEntity();

        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', $entity->setName('Test Name'));
    }

    public function testGetName()
    {
        $entity = new InstallationEntity();

        $entity->setName('Test Name');

        $this->assertSame('Test Name', $entity->getName());
    }

    public function testSetReturnUrl()
    {
        $entity = new InstallationEntity();

        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', $entity->setReturnUrl('Test Name'));
    }

    public function testGetReturnUrl()
    {
        $entity = new InstallationEntity();

        $entity->setReturnUrl('Test Name');

        $this->assertSame('Test Name', $entity->getReturnUrl());
    }

    public function testSetNotificationUrl()
    {
        $entity = new InstallationEntity();

        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', $entity->setNotificationUrl('Test Name'));
    }

    public function testGetNotificationUrl()
    {
        $entity = new InstallationEntity();

        $entity->setNotificationUrl('Test Name');

        $this->assertSame('Test Name', $entity->getNotificationUrl());
    }

    public function testSetDefaultProduct()
    {
        $entity = new InstallationEntity();

        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', $entity->setDefaultProduct('Test Name'));
    }

    public function testGetDefaultProduct()
    {
        $entity = new InstallationEntity();

        $entity->setDefaultProduct('Test Name');

        $this->assertSame('Test Name', $entity->getDefaultProduct());
    }

    public function testToArrayData()
    {
        $properties = [
            'id' => 'fdsfdfggsdf',
            'name' => 'dfvbfdegrwfb',
            'return_url' => 'http://test.com/fsbgdf',
            'notification_url' => 'http://test.com/ukykutj',
            'default_product' => 'WWW-34',
        ];

        $this->assertSame($properties, InstallationEntity::make($properties)->toArray());
    }
}
