<?php

namespace Reinder83\BinaryFlags\Traits;

use BackedEnum;
use Closure;
use Reinder83\BinaryFlags\Mask;
use InvalidArgumentException;

/**
 * Enum-focused mask/flag behavior.
 *
 * @template TEnum of BackedEnum
 */
trait InteractsWithEnumFlags
{
    /**
     * This will hold the mask for checking against
     */
    protected int $mask = 0;

    /**
     * This will be called on changes
     */
    protected ?Closure $onModifyCallback = null;

    /**
     * @return class-string<TEnum>
     */
    abstract protected static function getFlagEnumClass(): string;

    /**
     * @param int|TEnum|Mask<TEnum> $value
     */
    private function normalizeEnumMaskOrFlag(int|BackedEnum|Mask $value): int
    {
        if ($value instanceof BackedEnum) {
            $enumClass = static::getFlagEnumClass();
            if (!$value instanceof $enumClass) {
                throw new InvalidArgumentException(sprintf('Expected enum of type %s.', $enumClass));
            }

            if (!is_int($value->value)) {
                throw new InvalidArgumentException('Only int-backed enums are supported.');
            }

            return $value->value;
        }

        if ($value instanceof Mask) {
            if ($value->enumClass() !== static::getFlagEnumClass()) {
                throw new InvalidArgumentException(sprintf('Expected mask for enum %s.', static::getFlagEnumClass()));
            }

            return $value->toInt();
        }

        return $value;
    }

    /**
     * Return an array with all flags as key with a name as description
     *
     * @return array<int, string>
     */
    public static function getAllFlags(): array
    {
        $enumClass = static::getFlagEnumClass();
        if (!enum_exists($enumClass)) {
            return [];
        }

        /** @var array<int, TEnum> $cases */
        $cases = $enumClass::cases();

        $flags = [];
        foreach ($cases as $case) {
            if (!is_int($case->value)) {
                throw new InvalidArgumentException('Only int-backed enums are supported.');
            }
            $flags[(int) $case->value] = preg_replace('/(?<!^)[A-Z]/', ' $0', $case->name) ?? $case->name;
        }

        return $flags;
    }

    public static function getAllFlagsMask(): int
    {
        return array_reduce(
            array_keys(static::getAllFlags()),
            function (int $carry, int $flag): int {
                return $carry | $flag;
            },
            0,
        );
    }

    /**
     * Check mask against constants
     * and return the names or descriptions in a comma-separated string or as array
     *
     * @param int|TEnum|Mask<TEnum>|null $mask
     * @param bool $asArray
     * @return string|array<int, string>
     */
    public function getFlagNames(int|BackedEnum|Mask|null $mask = null, bool $asArray = false): string|array
    {
        $normalizedMask = $mask === null ? $this->mask : $this->normalizeEnumMaskOrFlag($mask);
        $names = [];

        foreach (static::getAllFlags() as $flag => $desc) {
            if (($normalizedMask & $flag) !== 0) {
                $names[$flag] = $desc;
            }
        }

        return $asArray ? $names : implode(', ', $names);
    }

    public function setOnModifyCallback(?Closure $onModify): void
    {
        $this->onModifyCallback = $onModify;
    }

    protected function onModify(): void
    {
        if (is_callable($this->onModifyCallback)) {
            call_user_func($this->onModifyCallback, $this);
        }
    }

    /**
     * @param int|TEnum|Mask<TEnum> $mask
     */
    public function setMask(int|BackedEnum|Mask $mask): static
    {
        $before = $this->mask;
        $this->mask = $this->normalizeEnumMaskOrFlag($mask);

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * @return Mask<TEnum>
     */
    public function getMask(): Mask
    {
        return Mask::fromInt($this->mask, static::getFlagEnumClass());
    }

    public function getMaskValue(): int
    {
        return $this->mask;
    }

    /**
     * @param int|TEnum|Mask<TEnum> $flag
     */
    public function addFlag(int|BackedEnum|Mask $flag): static
    {
        $before = $this->mask;
        $this->mask |= $this->normalizeEnumMaskOrFlag($flag);

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * @param int|TEnum|Mask<TEnum> $flag
     */
    public function removeFlag(int|BackedEnum|Mask $flag): static
    {
        $before = $this->mask;
        $normalizedFlag = $this->normalizeEnumMaskOrFlag($flag);
        $this->mask &= ~$normalizedFlag;

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * @param int|TEnum|Mask<TEnum> $flag
     */
    public function checkFlag(int|BackedEnum|Mask $flag, bool $checkAll = true): bool
    {
        $normalizedFlag = $this->normalizeEnumMaskOrFlag($flag);
        $result = $this->mask & $normalizedFlag;

        return $checkAll ? $result === $normalizedFlag : $result > 0;
    }

    /**
     * @param int|TEnum|Mask<TEnum> $mask
     */
    public function checkAnyFlag(int|BackedEnum|Mask $mask): bool
    {
        return $this->checkFlag($mask, false);
    }
}
