<?php

namespace Tests\Acceptance;

use AcceptanceTester;
use Helper\BaseCest;
use Page\LocationsPage;

/**
 * Class LocationsCest
 *
 * @author GK
 * @package Tests\Acceptance
 */
class LocationsCest extends BaseCest
{
    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToLocations(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the locations');
        $I->lookForwardTo('see the locations table');

        $this->login($I);
        $I->click('Locations');
        $I->see(LocationsPage::$pageTitle);
        foreach (LocationsPage::$tableTitles as $tableTitle) {
            $I->see($tableTitle);
        }
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     * @param LocationsPage $locationsPage
     */
    public function goToAddNewLocation(AcceptanceTester $I, LocationsPage $locationsPage)
    {
        $I->am('user');
        $I->wantTo('go to the add new location page');
        $I->lookForwardTo('see the the form for adding a new location page');

        $this->login($I);
        $locationsPage->goToAddNew($I);
        $I->see(LocationsPage::$pageAddTitle);
        foreach (LocationsPage::$addFormElements as $formElement) {
            $I->see($formElement);
        }
    }
}
