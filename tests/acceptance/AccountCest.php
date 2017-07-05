<?php

namespace Tests\Acceptance;

use AcceptanceTester;
use Helper\BaseCest;
use Page\AccountPage;
use Page\DashboardPage;

/**
 * Class AccountCest
 *
 * @author GK
 * @package Tests\Acceptance
 */
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
     * @param AccountPage $accountPage
     */
    public function goToAccountEdit(AcceptanceTester $I, AccountPage $accountPage)
    {
        $I->am('user');
        $I->wantTo('go to the account settings\'s edit page');
        $I->lookForwardTo('see the account setting\'s edit form');

        $this->login($I);
        $accountPage->goToEdit($I);
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
     * @param AccountPage $accountPage
     */
    public function changeName(AcceptanceTester $I, AccountPage $accountPage)
    {
        $I->am('user');
        $I->wantTo('Change my username');
        $I->lookForwardTo('See my new user name show up');

        $this->login($I);
        $accountPage->goToEdit($I);
        $I->fillField(AccountPage::$nameField, 'test username');
        $I->click(AccountPage::$updateDetailsButton);
        $I->see('Your details have successfully been changed');
        $I->see('test username');

        $I->fillField(AccountPage::$nameField, 'Administrator');
        $I->click(AccountPage::$updateDetailsButton);
        $I->see('Your details have successfully been changed');
        $I->see('Administrator');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     * @param AccountPage $accountPage
     */
    public function changePasswordSuccessfully(AcceptanceTester $I, AccountPage $accountPage)
    {
        $I->am('user');
        $I->wantTo('Change my password');
        $I->lookForwardTo('log in with my new password');

        $testPassword = 'test password';

        $this->login($I, $I::ROLE_ADMIN);
        $accountPage->goToEdit($I);
        $I->fillField(AccountPage::$oldPasswordField, $I::DEFAULT_PASSWORD);
        $I->fillField(AccountPage::$newPasswordField, $testPassword);
        $I->fillField(AccountPage::$confirmPasswordField, $testPassword);
        $I->click(AccountPage::$changePasswordButton);
        $I->see('Your password has successfully been changed');
        $this->logout($I);
        $this->login($I, $I::ROLE_ADMIN, $testPassword);
        $I->see(DashboardPage::$pageTitle);

        $accountPage->goToEdit($I);
        $I->fillField(AccountPage::$oldPasswordField, $testPassword);
        $I->fillField(AccountPage::$newPasswordField, $I::DEFAULT_PASSWORD);
        $I->fillField(AccountPage::$confirmPasswordField, $I::DEFAULT_PASSWORD);
        $I->click(AccountPage::$changePasswordButton);
        $I->see('Your password has successfully been changed');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     * @param AccountPage $accountPage
     */
    public function changePasswordUnsuccessfullyIncorrectOldPassword(AcceptanceTester $I, AccountPage $accountPage)
    {
        $I->am('user');
        $I->wantTo('Fail changing my password');
        $I->lookForwardTo('Getting a warning about the old password not being correct');

        $testPassword = 'test password';
        $mistypedDefaultPassword = 'typo' . $I::DEFAULT_PASSWORD;

        $this->login($I, $I::ROLE_ADMIN);
        $accountPage->goToEdit($I);
        $I->fillField(AccountPage::$oldPasswordField, $mistypedDefaultPassword);
        $I->fillField(AccountPage::$newPasswordField, $testPassword);
        $I->fillField(AccountPage::$confirmPasswordField, $testPassword);
        $I->click(AccountPage::$changePasswordButton);
        $I->see('Old password must match stored password');

        $this->logout($I);
        $this->login($I, $I::ROLE_ADMIN);
        $I->see(DashboardPage::$pageTitle);
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     * @param AccountPage $accountPage
     */
    public function changePasswordUnsuccessfullyNotMatchingNewPassword(AcceptanceTester $I, AccountPage $accountPage)
    {
        $I->am('user');
        $I->wantTo('Fail changing my password');
        $I->lookForwardTo('Getting a warning about not matching new passwords');

        $this->login($I, $I::ROLE_ADMIN);
        $accountPage->goToEdit($I);
        $I->fillField(AccountPage::$oldPasswordField, $I::DEFAULT_PASSWORD);
        $I->fillField(AccountPage::$newPasswordField, 'test password');
        $I->fillField(AccountPage::$confirmPasswordField, 'a different password');
        $I->click(AccountPage::$changePasswordButton);
        $I->see('The password and its confirm are not the same');

        $this->logout($I);
        $this->login($I, $I::ROLE_ADMIN);
        $I->see(DashboardPage::$pageTitle);
    }
}
