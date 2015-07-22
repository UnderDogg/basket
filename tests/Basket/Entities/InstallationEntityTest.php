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

use App\Basket\Entities\ApplicationEntity;
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
        $entity = ApplicationEntity::make(json_decode('
        {
    "id": 123,
    "posted_date": "2015-03-17T15:18:00Z",
    "current_status": "converted",
    "customer": {
        "title": "Mr",
        "first_name": "Fillibert",
        "last_name": "Labingi",
        "email_address": "fillibertlabingi+paybreak@gmail.com",
        "phone_home": null,
        "phone_mobile": "07700900124",
        "postcode": "TN12 6ZZ"
    },
    "application_address": {
        "abode": "Flat 2A",
        "building_name": "",
        "building_number": "1",
        "street": "Newtown Walk",
        "locality": "",
        "town": "Walmington-on-Sea",
        "postcode": "TN12 6ZZ"
    },
    "installation": "NoveltyRock",
    "order": {
        "reference": "NRE01234",
        "amount": 0,
        "description": "",
        "validity": ""
    },
    "products": {
        "group": "FF",
        "options": [
            "*"
        ],
        "default": "FF/1-3"
    },
    "fulfilment": {
        "method": "collection",
        "location": "Walmington-on-Sea Store"
    },
    "applicant": {
        "title": "Mr",
        "first_name": "Fillibert",
        "last_name": "Labingi",
        "date_of_birth": "1970-01-01",
        "email_address": "fillibert.labingi@gmail.com",
        "phone_home": null,
        "phone_mobile": "07700900123",
        "postcode": "TN12 6ZZ"
    },
    "finance": {
        "loan_amount": 0,
        "order_amount": 0,
        "deposit_amount": 0,
        "subsidy_amount": 0,
        "settlement_net_amount": 0
    },
    "metadata": {
        "you": "do",
        "what_ever": "you",
        "want": 2
    }
}
        ', true));

        echo $entity->toJson(JSON_PRETTY_PRINT);

die();

        $entity = new InstallationEntity();

        $this->assertInstanceOf('WNowicki\Generic\Contracts\Entity', $entity);
        $this->assertInstanceOf('App\Basket\Entities\InstallationEntity', $entity);
    }

    public function testMakeEmpty()
    {
        $this->assertInstanceOf('WNowicki\Generic\Contracts\Entity', InstallationEntity::make([]));
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
