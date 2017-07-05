<?php

namespace Page;

use AcceptanceTester;

/**
 * Class LoginPage
 *
 * @author GK
 * @package Page
 */
class LoginPage
{
    // include url of current page
    public static $URL = '/login';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $emailField = 'email';
    public static $passwordField = 'password';
    public static $signInButton = 'Sign In';

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
     * @param string|null $password
     */
    public static function login(AcceptanceTester $I, $password = null)
    {
        $I->amOnPage(self::$URL);
        $I->fillField(self::$emailField, $I->getLoginName());
        $I->fillField(self::$passwordField, is_null($password) ? $I::DEFAULT_PASSWORD : $password);
        $I->click(self::$signInButton);
    }

    /**
     * @author GK
     * @param \AcceptanceTester $I
     */
    public static function logout(AcceptanceTester $I)
    {
        $I->click('Logout');
    }
}
