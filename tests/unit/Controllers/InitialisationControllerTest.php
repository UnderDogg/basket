<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\User;
use App\Http\Controllers;

class InitialisationControllerTest extends TestCase
{
    /**
     * @author WN
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DevSeeder']);

        $user = User::find(1);
        $this->be($user);
    }

    /**
     * @author WN
     */
    public function testShow()
    {
        // Test page gives 200 response
        $this->visit('/account')
            ->seeStatusCode(200);
    }

    /**
     * @author WN
     */
    public function testEdit()
    {
        // Test page gives 200 response
        $this->visit('/account/edit')
            ->seeStatusCode(200);
    }

    /**
     * @author EB
     */
    public function testPrepareWithoutPermissionForLocation()
    {
        $user = User::find(1);
        $this->visit('/locations/1/applications/make')
            ->see('You don\'t have permission to access this Location')
            ->seeStatusCode(200);
    }

    /**
     * @author EB
     */
    public function testPrepareWithAccessToLocationWithValidFinanceOfferRoute()
    {
        $user = User::find(1);
        $user->locations()->sync([1]);
        $this->visit('/locations/1/applications/make')
            ->see('Interested In Finance?')
            ->seeStatusCode(200);
    }

    /**
     * @author EB
     */
    public function testPrepareWithAccessToLocationWithoutValidFinanceOfferRoute()
    {
        $user = User::find(1);
        $user->locations()->sync([1]);
        $installation = \App\Basket\Location::find(1)->installation;
        $installation->finance_offers = 0;
        $installation->save();
        $this->visit('/locations/1/applications/make')
            ->see('Cannot make an application for location [1]')
            ->seeStatusCode(200);
    }
}
