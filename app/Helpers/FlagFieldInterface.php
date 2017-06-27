<?php

namespace App\Helpers;

/**
 * Class AbstractBitwiser
 *
 * @package Flow\Bitwiser
 */
interface FlagFieldInterface
{
    /**
     * Has Option
     *
     * @param int $flag
     * @return bool
     */
    public function has($flag);

    /**
     * ! Has Flag
     *
     * @param int $flag
     * @return bool
     */
    public function hasNot($flag);

    /**
     * Get Named State Array
     *
     * @param bool $named
     * @return array
     */
    public function state($named = true);

    /**
     * Add a flag
     *
     * @param int $flag
     * @return $this
     */
    public function add($flag);

    /**
     * Remove a flag
     *
     * @param $flag
     * @return $this
     */
    public function remove($flag);

    /**
     * Set the onChange callback
     *
     * @param callable $callback
     */
    public function setOnChangeCallback(callable $callback);

    /**
     * Get the state integer value
     *
     * @return int
     */
    public function getState();

    /**
     * Set the state integer value
     *
     * @param $state
     * @return $this
     */
    public function setState($state);

    public function jsonSerialize();
}
