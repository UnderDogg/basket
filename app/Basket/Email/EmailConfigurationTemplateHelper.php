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
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->configuration = $params;
    }

    /**
     * @param $jsonConfiguration
     * @return EmailConfigurationTemplateHelper
     */
    public static function makeFromJson($jsonConfiguration)
    {
        $config = [];

        if (!empty($jsonConfiguration)) {
            $config = json_decode($jsonConfiguration, JSON_OBJECT_AS_ARRAY);
        }

        if (is_null($config)) {
            $config = [];
        }

        return new self($config);
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
     * @param string $default
     * @return string
     */
    public function getSafe($field, $default = '')
    {
        if (!$this->has($field)) {
            return $default;
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
    public function toArray()
    {
        return $this->configuration;
    }
}
