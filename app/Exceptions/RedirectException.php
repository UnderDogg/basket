<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exceptions;

/**
 * Redirect Exception
 *
 * @author WN
 * @package App\Exceptions
 */
class RedirectException extends \Exception
{
    private $target = '/';
    private $confirmation;
    private $error;
    private $information;

    /**
     * @author WN
     * @param string $target
     * @return $this
     */
    public static function make($target)
    {
        return (new self())->setTarget($target);
    }

    /**
     * @author WN
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @author WN
     * @param string $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @author WN
     * @return string
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * @author WN
     * @param string $confirmation
     * @return $this
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
        return $this;
    }

    /**
     * @author WN
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @author WN
     * @param mixed $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @author WN
     * @return mixed
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @author WN
     * @param mixed $information
     * @return $this
     */
    public function setInformation($information)
    {
        $this->information = $information;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $rtn = [];
        if ($this->getError()) {
            $rtn['error'] = $this->getError();
        }
        return $rtn;
    }
}
