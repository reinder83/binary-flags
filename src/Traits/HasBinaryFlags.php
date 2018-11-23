<?php

namespace Reinder83\BinaryFlags\Traits;

use Reinder83\BinaryFlags\Flag;

/**
 * This trait holds useful methods for checking, adding or removing binary flags
 *
 * @author Reinder
 */
trait HasBinaryFlags
{
    /**
     * This will hold the mask for checking against
     *
     * @var int
     */
    protected $mask = 0;

    /**
     * Listeners that can modify the actual mask
     *
     * @var array
     */
    protected $binaryBeforeEventListeners = [];

    /**
     * Listeners that can do something with the end result
     *
     * @var array
     */
    protected $binaryAfterEventListeners = [];

    /**
     * Array containing custom flag names
     *
     * @var array
     */
    protected $binaryCustomFlagNames = [];

    /**
     * Check mask against constants
     * and return the names or descriptions as array
     *
     * @param int|null $mask
     *
     * @return array
     */
    public function getFlags(int $mask = null): array
    {
        $mask = $mask ?: $this->mask;

        $constants = class_constants(static::class);

        $names = [];

        foreach ($constants as $constant => $flag) {

            if (!Flag::has($mask, $flag)) {
                continue;
            }

            $names[$flag] = $this->binaryCustomFlagNames[$flag] ?? $constant;
        }

        return $names;
    }

    /**
     * This will set flag(s) in the current mask
     *
     * @param array|int $flags
     *
     * @return HasBinaryFlags
     */
    public function addFlag(...$flags): self
    {
        return $this->setMask(Flag::add($this->mask, ...$flags));
    }

    /**
     * This will remove a flag(s) (if it's set) in the current mask
     *
     * @param array|int $flags
     *
     * @return HasBinaryFlags
     */
    public function removeFlag(...$flags): self
    {
        return $this->setMask(Flag::remove($this->mask, ...$flags));
    }

    /**
     * Check if given flag(s) are set in the current mask
     *
     * @param int|array $flags
     *
     * @return bool
     */
    public function hasFlag(...$flags): bool
    {
        return Flag::has($this->mask, ...$flags);
    }

    /**
     * Check if any given flag(s) are set in the current mask
     *
     * @param int|array $flags
     *
     * @return bool
     */
    public function hasAnyFlag(...$flags): bool
    {
        return Flag::hasAny($this->mask, ...$flags);
    }

    /**
     * This method will set the mask where will be checked against
     *
     * @param int $mask
     *
     * @return self
     */
    public function setMask(int $mask): self
    {
        $old = $this->mask;

        $response = $this->fireBeforeEvent($mask, $old);

        // When response is false we do nothing as the change was rejected
        if ($response === false) {
            return $this;
        }

        // When response is an int the change was overridden by the event listeners
        if (is_int($response)) {
            $mask = $response;
        }

        // Set the actual mask
        $this->mask = $mask;

        $this->fireAfterEvent($mask, $old);

        return $this;
    }

    /**
     * This method will return the current mask
     *
     * @return int
     */
    public function getMask(): int
    {
        return $this->mask;
    }

    /**
     * @param array $names
     *
     * @return \Reinder83\BinaryFlags\Traits\HasBinaryFlags
     */
    public function setCustomFlagNames(array $names): self
    {
        $this->binaryCustomFlagNames = $names;

        return $this;
    }

    /**
     * When used in a laravel model this is wat modifies the actual attribute
     */
    public function initializeHasBinaryFlags()
    {
        // We directly set the mask that was retrieved from the database
        $this->mask = (int)$this->getAttribute($this->flagsColumn()) ?: 0;

        $this->addAfterEventListener(function (int $new, int $old) {
            $this->setAttribute($this->flagsColumn(), $new);
        });
    }

    /**
     * @return string
     */
    public function flagsColumn()
    {
        return $this->flagsField ?? 'flags';
    }
    /**
     * Add listener for changes
     *
     * @param callable $callable
     *
     * @return self
     */
    public function addBeforeEventListener(callable $callable): self
    {
        $this->binaryBeforeEventListeners[] = $callable;

        return $this;
    }

    /**
     * Add listener for changes
     *
     * @param callable $callable
     *
     * @return self
     */
    public function addAfterEventListener(callable $callable): self
    {
        $this->binaryAfterEventListeners[] = $callable;

        return $this;
    }

    /**
     * Will be called upon changes and execute the callback, if set
     *
     * @param int $old
     * @param int $new
     *
     * @return bool|int|null
     */
    protected function fireBeforeEvent(int $new, int $old)
    {
        if ($new === $old) {
            return false;
        }

        $return = null;

        foreach ($this->binaryBeforeEventListeners as $listener) {
            $response = call_user_func($listener, $new, $old);

            if ($response === false) {
                return false;
            }

            if (is_int($response)) {
                $new = $return = $response;
            }
        }

        return $return;
    }

    /**
     * Will be called upon changes and execute the callback, if set
     *
     * @param int $new
     *
     * @param int $old
     *
     * @return void
     */
    protected function fireAfterEvent(int $new, int $old): void
    {
        foreach ($this->binaryAfterEventListeners as $listener) {
            call_user_func($listener, $new, $old);
        }
    }

}