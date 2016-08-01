<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket\Email;

use App\Exceptions\Exception;

/**
 * Class EmailConfigurationTemplateHelper
 *
 * @package App\Basket\Email
 * @author SL
 */
class EmailConfigurationTemplateHelper
{
    private $configuration = [];

    /**
     * EmailConfigurationTemplateHelper constructor.
     * @param string $jsonConfiguration
     */
    public function __construct($jsonConfiguration)
    {
        if (!empty($jsonConfiguration)) {
            $this->configuration = json_decode($jsonConfiguration, JSON_OBJECT_AS_ARRAY);
        }

        if (is_null($this->configuration)) {
            $this->configuration = [];
        }
    }

    /**
     * @author SL
     * @param $field
     * @return bool
     */
    public function has($field)
    {
        if (array_key_exists($field, $this->configuration)) {

            return true;
        }

        return false;
    }

    /**
     * @author SL
     * @param $field
     * @return string
     */
    public function getSafe($field)
    {
        if (!$this->has($field)) {

            return '';
        }

        return $this->configuration[$field];
    }

    /**
     * @author SL
     * @param string $field
     * @return mixed
     * @throws Exception
     */
    public function get($field)
    {
        if (!$this->has($field)) {

            throw new Exception('Attempted to access a non existing configuration element [' . $field . ']');
        }

        return $this->configuration[$field];
    }

    /**
     * @author SL
     * @return array
     */
    public function getRaw()
    {
        return $this->configuration;
    }
}
