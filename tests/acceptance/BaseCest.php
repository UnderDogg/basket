<?php

namespace Tests\Acceptance;

use AcceptanceTester;
use Page\LoginPage;

/**
 * Class BaseCest
 *
 * @author GK
 * @package Tests\Acceptance
 */
class BaseCest
{
    const ROLE_ADMIN = 0;
    const ROLE_MERCHANTADMINISTRATOR = 1;
    const ROLE_REPORTER = 2;
    const ROLE_MANAGER = 3;
    const ROLE_SALES = 4;

    /**
     * BaseCest constructor.
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     * @param int $role
     */
    protected function login(AcceptanceTester &$I, $role = self::ROLE_ADMIN)
    {
        LoginPage::login($I, $role);
    }

    /**
     * @author GK
     * @param AcceptanceTester $I
     */
    protected function logout(AcceptanceTester &$I)
    {
        LoginPage::logout($I);
    }
}
