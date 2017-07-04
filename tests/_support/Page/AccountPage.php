<?php

namespace Page;

use AcceptanceTester;

/**
 * Class AccountPage
 *
 * @author GK
 * @package Page
 */
class AccountPage
{
    // include url of current page
    public static $URL = '/account';

    public static $menuButton = 'Account';
    public static $editButton = 'Edit';

    public static $nameField = 'name';
    public static $emailField = 'email';
    public static $updateDetailsButton = 'Update details';
    public static $oldPasswordField = 'old_password';
    public static $newPasswordField = 'new_password';
    public static $confirmPassword = 'new_password_confirmation';
    public static $changePasswordButton = '';

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

    public static function goToEdit(AcceptanceTester $I)
    {
        $I->click(AccountPage::$menuButton);
        $I->click(AccountPage::$editButton);
    }
}
