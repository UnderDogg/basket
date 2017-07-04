<?php

namespace Tests\Acceptance;

use AcceptanceTester;
use Helper\BaseCest;
use Page\AccountPage;

class AccountCest extends BaseCest
{
    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToAccount(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the account settings');
        $I->lookForwardTo('see the account settings');
        $this->login($I);
        $I->click(AccountPage::$menuButton);
        $I->see('Account management');
        $I->see('User Details');
        $I->see('Name:');
        $I->see('Email:');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToAccountEdit(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the account settings\'s edit page');
        $I->lookForwardTo('see the account setting\'s edit form');
        $this->login($I);
        AccountPage::goToEdit($I);
        $I->see('Edit account details');
        $I->see('User Details');
        $I->see('Name:');
        $I->see('Email:');
        $I->see('Old password:');
        $I->see('New password:');
        $I->see('Confirm password:');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function changeName(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('Change my password');
        $I->lookForwardTo('log in with my new password');
        $this->login($I);
        AccountPage::goToEdit($I);
        $I->fillField(AccountPage::$nameField, 'test');
        $I->click(AccountPage::$updateDetailsButton);
        $I->see('Your details have successfully been changed');
        $I->fillField(AccountPage::$nameField, 'Administrator');
        $I->click(AccountPage::$updateDetailsButton);
        $I->see('Your details have successfully been changed');
    }
}
