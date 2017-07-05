<?php

namespace Page;

use AcceptanceTester;

/**
 * Class LocationsPage
 *
 * @author GK
 * @package Page
 */
class LocationsPage
{
    // include url of current page
    public static $URL = '/locations';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $pageTitle = 'Locations';
    public static $pageAddTitle = 'Create Location';
    public static $pageEditTitle = 'Edit Location';
    public static $pageDeleteTitle = 'Delete Location';

    public static $menuButton = 'Locations';
    public static $addButton = 'Add New Location';
    public static $submitAddFormButton = 'Create Location';
    public static $filterButton = 'FILTER';
    public static $viewButton = 'View';
    public static $editButton = 'Edit';
    public static $deleteButton = 'Delete';

    public static $nameField = 'name';
    public static $emailField = 'email';
    public static $oldPasswordField = 'old_password';
    public static $newPasswordField = 'new_password';
    public static $confirmPasswordField = 'new_password_confirmation';

    public static $tableTitles = [
        'Reference',
        'Name',
        'Installation',
        'Active',
        'Actions'
    ];
    public static $addFormElements = [
        'Reference:',
        'Installation:',
        'Active:',
        'Name:',
        'Email',
        'Address:',
        'Create Location'
    ];

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
    public function goToAddNew(AcceptanceTester $I)
    {
        $I->click(self::$menuButton);
        $I->click(self::$addButton);
    }
}
