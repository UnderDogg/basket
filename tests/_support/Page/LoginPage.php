<?php
namespace Page;

use Tests\Acceptance\BaseCest;

class LoginPage
{
    // include url of current page
    public static $URL = '/login';

    public static $emailField = 'email';
    public static $passwordField = 'password';
    public static $signInButton = 'Sign In';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @author GK
     * @param \AcceptanceTester $I
     * @param int $role
     */
    public static function login(\AcceptanceTester $I, $role)
    {
        $I->amOnPage(self::$URL);
        $I->fillField(self::$emailField, self::getLoginName($role));
        $I->fillField(self::$passwordField, 'password');
        $I->click(self::$signInButton);
    }

    /**
     * @author GK
     * @param \AcceptanceTester $I
     */
    public static function logout(\AcceptanceTester $I)
    {
        $I->click('Logout');
    }

    /**
     * @author GK
     * @param $role
     * @return string
     */
    private static function getLoginName($role)
    {
        switch ($role) {
            case BaseCest::ROLE_ADMIN:
                return 'dev@paybreak.com';
            case BaseCest::ROLE_MERCHANTADMINISTRATOR:
                return 'it@paybreak.com';
            case BaseCest::ROLE_REPORTER:
                return 'report@paybreak.com';
            case BaseCest::ROLE_MANAGER:
                return 'manager@paybreak.com';
            case BaseCest::ROLE_SALES:
                return 'sales@paybreak.com';
        }
    }
}
