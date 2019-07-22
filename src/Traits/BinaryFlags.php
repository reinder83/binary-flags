<?php

namespace Reinder83\BinaryFlags\Traits;

use ReflectionClass;
use ReflectionException;

/**
 * This trait holds useful methods for checking, adding or removing binary flags
 *
 * @author Reinder
 */
trait BinaryFlags
{
    /**
     * This will hold the mask for checking against
     * @var int
     */
    protected $mask;

    /**
     * This will be called on changes
     * @var callable
     */
    protected $onModifyCallback;

    /**
     * Return an array with all flags as key with a name as description
     *
     * @return array
     */
    public static function getAllFlags()
    {
        try {
            $reflection = new ReflectionClass(get_called_class());
            // @codeCoverageIgnoreStart
        } catch (ReflectionException $e) {
            return [];
        }
        // @codeCoverageIgnoreEnd

        $constants = $reflection->getConstants();

        $flags = [];
        if ($constants) {
            foreach ($constants as $constant => $flag) {
                if (is_numeric($flag)) {
                    $flags[$flag] = implode('', array_map('ucfirst', explode('_', strtolower($constant))));
                }
            }
        }

        return $flags;
    }

    /**
     * Get all available flags as a mask
     */
    public static function getAllFlagsMask()
    {
        return array_reduce(
            array_keys(static::getAllFlags()),
            function ($flag, $carry) {
                return $carry | $flag;
            },
            0
        );
    }

    /**
     * Check mask against constants
     * and return the names or descriptions in a comma separated string or as array
     *
     * @param int [$mask = null]
     * @param bool [$asArray = false]
     * @return string|array
     */
    public function getFlagNames($mask = null, $asArray = false)
    {
        $mask  = isset($mask) ? $mask : $this->mask;
        $names = [];

        foreach (static::getAllFlags() as $flag => $desc) {
            if (is_numeric($flag) && ($mask & $flag)) {
                $names[$flag] = $desc;
            }
        }

        return $asArray ? $names : implode(', ', $names);
    }

    /**
     * Set an function which will be called upon changes
     *
     * @param callable $onModify
     */
    public function setOnModifyCallback(callable $onModify)
    {
        $this->onModifyCallback = $onModify;
    }

    /**
     * Will be called upon changes and execute the callback, if set
     */
    protected function onModify()
    {
        if (is_callable($this->onModifyCallback)) {
            call_user_func($this->onModifyCallback, $this);
        }
    }

    /**
     * This method will set the mask where will be checked against
     *
     * @param int $mask
     * @return BinaryFlags
     */
    public function setMask($mask)
    {
        $before     = $this->mask;
        $this->mask = $mask;

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * This method will return the current mask
     *
     * @return int
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * This will set flag(s) in the current mask
     *
     * @param int $flag
     * @return BinaryFlags
     */
    public function addFlag($flag)
    {
        $before     = $this->mask;
        $this->mask |= $flag;

        if ($before !== $this->mask) {
            $this->onModify();
        }
        return $this;
    }

    /**
     * This will remove a flag(s) (if it's set) in the current mask
     *
     * @param int $flag
     * @return BinaryFlags
     */
    public function removeFlag($flag)
    {
        $before     = $this->mask;
        $this->mask &= ~$flag;

        if ($before !== $this->mask) {
            $this->onModify();
        }
        return $this;
    }

    /**
     * Check if given flag(s) are set in the current mask
     * By default it will check all bits in the given flag
     * When you want to match any of the given flags set $checkAll to false
     *
     * @param int $flag
     * @param bool $checkAll
     * @return bool
     */
    public function checkFlag($flag, $checkAll = true)
    {
        $result = $this->mask & $flag;
        return $checkAll ? $result == $flag : $result > 0;
    }

    /**
     * Check if any given flag(s) are set in the current mask
     *
     * @param int $mask
     * @return bool
     */
    public function checkAnyFlag($mask)
    {
        return $this->checkFlag($mask, false);
    }
}
