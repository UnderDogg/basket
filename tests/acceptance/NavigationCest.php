<?php

namespace Tests\Acceptance;

use AcceptanceTester;
use Page\DashboardPage;
use Page\LoginPage;
use Helper\BaseCest;

/**
 * Class NavigationCest
 *
 * @author GK
 * @package Tests\Acceptance
 */
class NavigationCest extends BaseCest
{
    /**
     * @author GK
     * @param AcceptanceTester $I
     * @param LoginPage $loginPage
     * @param DashboardPage $dashboard
     */
    public function signInAndLogout(AcceptanceTester $I, LoginPage $loginPage, DashboardPage $dashboard)
    {
        $I->am('user');
        $I->wantTo('login to website');
        $I->lookForwardTo('access website features for logged-in users');

        $loginPage::login($I);
        $I->see($dashboard::$pageTitle);
        $I->see('Support Details');
        $loginPage::logout($I);
        $I->see('Sign In');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToApplications(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the applications');
        $I->lookForwardTo('see the applications table');

        $this->login($I);
        $I->click('Applications');
        $I->see('Applications');
        $I->see('ID');
        $I->see('Received');
        $I->see('Current Status');
        $I->see('Retailer Reference');
        $I->see('Finance Group');
        $I->see('Retailer Liable');
        $I->see('First Name');
        $I->see('Last Name');
        $I->see('Postcode');
        $I->see('Order Amount');
        $I->see('Loan Amount');
        $I->see('Deposit');
        $I->see('Subsidy');
        $I->see('Commission');
        $I->see('Net Settlement');
        $I->see('Location');
        $I->see('Email');
        $I->see('Actions');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToPendingCancellations(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the pending cancellations');
        $I->lookForwardTo('see the pending cancellations table');

        $this->login($I);
        $I->click('Pending Cancellations');
        $I->see('Pending Cancellations');
        $I->see('Retailer Ref.');
        $I->see('Name');
        $I->see('Cancellation Fee');
        $I->see('Cancelled Reason');
        $I->see('Requested');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToSettlementReports(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the settlement reports');
        $I->lookForwardTo('see the settlement reports table');

        $this->login($I);
        $I->click('Settlements');
        $I->see('Settlement Reports');
        $I->see('Report ID');
        $I->see('Settlement Date');
        $I->see('Lender');
        $I->see('Amount');
        $I->see('Actions');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToPartialRefunds(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the partial refunds');
        $I->lookForwardTo('see the partial refunds table');

        $this->login($I);
        $I->click('Partial Refunds');
        $I->see('Partial Refunds');
        $I->see('Application ID');
        $I->see('Status');
        $I->see('Refund Amount');
        $I->see('Requested Date');
        $I->see('Actions');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToInstallations(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the installations');
        $I->lookForwardTo('see the installations table');

        $this->login($I);
        $I->click('Installations');
        $I->see('Installations');
        $I->see('Name');
        $I->see('Active');
        $I->see('Linked');
        $I->see('Merchant Liability');
        $I->see('Actions');
    }

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
        $I->see('Locations');
        $I->see('Reference');
        $I->see('Name');
        $I->see('Installation');
        $I->see('Active');
        $I->see('Actions');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToUsers(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the users');
        $I->lookForwardTo('see the users table');

        $this->login($I);
        $I->click('Users');
        $I->see('Users');
        $I->see('Name');
        $I->see('Email');
        $I->see('Merchant');
        $I->see('Actions');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToMerchants(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the merchants');
        $I->lookForwardTo('see the merchants table');

        $this->login($I);
        $I->click('Merchants');
        $I->see('Merchants');
        $I->see('Name');
        $I->see('Linked');
        $I->see('Actions');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    public function goToRoles(AcceptanceTester $I)
    {
        $I->am('user');
        $I->wantTo('go to the roles and permissions');
        $I->lookForwardTo('see the roles table');

        $this->login($I);
        $I->click('Roles & Permissions');
        $I->see('Roles');
        $I->see('ID');
        $I->see('Display Name');
        $I->see('Role Code');
        $I->see('Actions');
    }
}
