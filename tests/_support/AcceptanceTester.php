<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    const ROLE_ADMIN = 0;
    const ROLE_MERCHANTADMINISTRATOR = 1;
    const ROLE_REPORTER = 2;
    const ROLE_MANAGER = 3;
    const ROLE_SALES = 4;

    const DEFAULT_PASSWORD = 'password';

    private $role;

    /**
     * AcceptanceTester constructor.
     *
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(\Codeception\Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->setRole(self::ROLE_ADMIN);
    }

    /**
    * Define custom actions here
    */

    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @author GK
     * @return string
     */
    public function getLoginName()
    {
        switch ($this->role) {
            case self::ROLE_ADMIN:
                return 'dev@paybreak.com';
            case self::ROLE_MERCHANTADMINISTRATOR:
                return 'it@paybreak.com';
            case self::ROLE_REPORTER:
                return 'report@paybreak.com';
            case self::ROLE_MANAGER:
                return 'manager@paybreak.com';
            case self::ROLE_SALES:
                return 'sales@paybreak.com';
        }
    }
}
