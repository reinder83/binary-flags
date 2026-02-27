<?php

namespace Reinder83\BinaryFlags\Traits;

use Closure;
use ReflectionClass;
use ReflectionException;

/**
 * Preferred trait for numeric mask/flag behavior.
 */
trait InteractsWithNumericFlags
{
    /**
     * This will hold the mask for checking against
     */
    protected int|float $mask = 0;

    /**
     * This will be called on changes
     */
    protected ?Closure $onModifyCallback = null;

    /**
     * Emits a deprecation warning for float masks/flags and normalizes the value to int.
     */
    private function normalizeMaskOrFlag(int|float $value, string $parameter, string $method): int
    {
        if (is_float($value)) {
            trigger_error(
                sprintf(
                    'Passing float as $%s to %s() is deprecated since 2.1.0 and will be removed in 3.0.0. Cast to int before calling.',
                    $parameter,
                    $method,
                ),
                E_USER_DEPRECATED,
            );
        }

        return (int) $value;
    }

    /**
     * Return an array with all flags as key with a name as description
     *
     * @return array<int, string>
     */
    public static function getAllFlags(): array
    {
        try {
            $reflection = new ReflectionClass(static::class);
            // @codeCoverageIgnoreStart
        } catch (ReflectionException) {
            return [];
        }
        // @codeCoverageIgnoreEnd

        $constants = $reflection->getConstants();

        $flags = [];
        if ($constants) {
            foreach ($constants as $constant => $flag) {
                if (is_numeric($flag)) {
                    $flags[(int) $flag] = implode('', array_map('ucfirst', explode('_', strtolower($constant))));
                }
            }
        }

        return $flags;
    }

    /**
     * Get all available flags as a mask
     */
    public static function getAllFlagsMask(): int|float
    {
        return array_reduce(
            array_keys(static::getAllFlags()),
            function ($carry, $flag) {
                return $carry | $flag;
            },
            0,
        );
    }

    /**
     * Check mask against constants
     * and return the names or descriptions in a comma-separated string or as array
     *
     * @return string|array<int, string>
     */
    public function getFlagNames(int|float|null $mask = null, bool $asArray = false): string|array
    {
        $mask = $mask === null
            ? (int) $this->mask
            : $this->normalizeMaskOrFlag($mask, 'mask', __METHOD__);

        $names = [];

        foreach (static::getAllFlags() as $flag => $desc) {
            if (is_numeric($flag) && ($mask & $flag)) {
                $names[$flag] = $desc;
            }
        }

        return $asArray ? $names : implode(', ', $names);
    }

    /**
     * Set a function which will be called upon changes
     */
    public function setOnModifyCallback(?Closure $onModify): void
    {
        $this->onModifyCallback = $onModify;
    }

    /**
     * Will be called upon changes and execute the callback, if set
     */
    protected function onModify(): void
    {
        if (is_callable($this->onModifyCallback)) {
            call_user_func($this->onModifyCallback, $this);
        }
    }

    /**
     * This method will set the mask where will be checked against
     *
     * @return $this
     */
    public function setMask(int|float $mask): static
    {
        $before = $this->mask;
        $this->mask = $this->normalizeMaskOrFlag($mask, 'mask', __METHOD__);

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * This method will return the current mask
     */
    public function getMask(): int|float
    {
        return $this->mask;
    }

    /**
     * This will set flag(s) in the current mask
     *
     * @return $this
     */
    public function addFlag(int|float $flag): static
    {
        $before = $this->mask;
        $this->mask |= $this->normalizeMaskOrFlag($flag, 'flag', __METHOD__);

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * This will remove a flag(s) (if it's set) in the current mask
     *
     * @return $this
     */
    public function removeFlag(int|float $flag): static
    {
        $before = $this->mask;
        $normalizedFlag = $this->normalizeMaskOrFlag($flag, 'flag', __METHOD__);
        $this->mask &= ~$normalizedFlag;

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * Check if given flag(s) are set in the current mask
     * By default it will check all bits in the given flag
     * When you want to match any of the given flags set $checkAll to false
     */
    public function checkFlag(int|float $flag, bool $checkAll = true): bool
    {
        $normalizedFlag = $this->normalizeMaskOrFlag($flag, 'flag', __METHOD__);
        $result = $this->mask & $normalizedFlag;

        return $checkAll ? $result === $normalizedFlag : $result > 0;
    }

    /**
     * Check if any given flag(s) are set in the current mask
     */
    public function checkAnyFlag(int|float $mask): bool
    {
        $normalizedMask = $this->normalizeMaskOrFlag($mask, 'mask', __METHOD__);

        return $this->checkFlag($normalizedMask, false);
    }
}
