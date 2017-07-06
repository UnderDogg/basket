<?php

namespace Helper;

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
     * @param string $password
     */
    protected function login(AcceptanceTester &$I, $role = AcceptanceTester::ROLE_ADMIN, $password = null)
    {
        $I->setRole($role);
        LoginPage::login($I, $password);
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
