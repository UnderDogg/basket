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

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $menuButton = 'Account';
    public static $editButton = 'Edit';
    public static $updateDetailsButton = 'Update details';
    public static $changePasswordButton = 'Change password';

    public static $nameField = 'name';
    public static $emailField = 'email';
    public static $oldPasswordField = 'old_password';
    public static $newPasswordField = 'new_password';
    public static $confirmPasswordField = 'new_password_confirmation';

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
     * @param AcceptanceTester $I
     */
    public function goToEdit(AcceptanceTester $I)
    {
        $I->click(self::$menuButton);
        $I->click(self::$editButton);
    }
}
