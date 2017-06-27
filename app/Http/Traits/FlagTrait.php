<?php

namespace App\Http\Traits;

use App\Helpers\FlagFieldInterface;
use App\Exceptions\InvalidTypeException;
use Flow\Bitwiser\AbstractBitwiser;

/**
 * Trait FlagTrait
 *
 * This trait must only be used on classes extending eloquent models
 * @package App\Http\Traits
 */
trait FlagTrait
{
    /**
     * Return the fields addressed by a flagged type:
     * ['field_name' => FlagFieldInterface::class]
     * @author GK
     * @return array
     */
    abstract protected function getFlagFields();

    /**
     * @author GK
     * @param $key
     * @return mixed
     */
    abstract protected function getAttribute($key);

    /**
     * Call in the fill method if you want the properties to be always set
     *
     * @author GK
     * @param array $attributes
     * @return array
     */
    protected function forceFillFlags(array $attributes)
    {
        foreach ($this->getFlagFields() as $property => $class) {
            if (!array_key_exists($property, $attributes)) {
                $attributes[$property] = [];
            }
        }

        return $attributes;
    }

    /**
     * Stores singletons of flag objects to reduce memory overhead.
     * @var array
     */
    private $flagSingletons = [];

    /**
     * @author GK
     * @param $key
     * @param $value
     * @return void
     * @throws \Exception
     */
    protected function setFlagAttribute($key, $value)
    {
        if (is_array($value)) {
            $flags = $this->makeFlagObject($key);

            foreach ($value as $bit) {
                $flags->add($bit);
            }

            $this->attributes[$key] = $flags->getState();
            return;
        }

        throw new InvalidTypeException(
            'Unexpected type provided for property [' . $key . '] on [' . self::class . '] object. ' .
            'Expected [array], got [' . gettype($value) . ']'
        );
    }

    /**
     * @author GK
     * @param $key
     * @return FlagFieldInterface
     */
    private function getFlagAttribute($key)
    {
        return $this->makeFlagObject($key)->setState($this->getAttribute($key));
    }

    /**
     * @author GK
     * @param $field
     * @return bool
     */
    private function fieldIsFlag($field)
    {
        return array_key_exists($field, $this->getFlagFields());
    }

    /**
     * @author GK
     * @param $key
     * @return FlagFieldInterface
     * @throws InvalidTypeException
     */
    private function makeFlagObject($key)
    {
        if (array_key_exists($key, $this->flagSingletons)) {
            return $this->flagSingletons[$key];
        }

        $type = $this->getFlagFields()[$key];

        if (!(new $type() instanceof FlagFieldInterface)) {
            throw new InvalidTypeException('The provided type does not implement [FlagFieldInterface]');
        }

        $status = 0;
        $this->flagSingletons[$key] = new $type($status, function (AbstractBitwiser $flags) use ($key) {
            $this->attributes[$key] = $flags->getState();
        });

        return $this->flagSingletons[$key];
    }

    /**
     * @author GK
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($this->fieldIsFlag($key)) {
            return $this->getFlagAttribute($key);
        }

        return $this->getAttribute($key);
    }
}
