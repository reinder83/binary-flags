<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags;

use BackedEnum;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Immutable enum-mask value object.
 *
 * @template TEnum of BackedEnum
 * @implements IteratorAggregate<int, TEnum>
 */
final class Mask implements Countable, IteratorAggregate, JsonSerializable
{
    /** @var class-string<TEnum> */
    private string $enumClass;

    /** @var array<int, TEnum> */
    private array $flags;

    /**
     * @param class-string<TEnum> $enumClass
     * @param array<int|string, TEnum> $flags
     */
    private function __construct(string $enumClass, array $flags)
    {
        $normalized = [];
        foreach ($flags as $flag) {
            if (!$flag instanceof $enumClass) {
                throw new InvalidArgumentException(sprintf('Expected enum of type %s.', $enumClass));
            }

            if (!is_int($flag->value)) {
                throw new InvalidArgumentException('Only int-backed enums are supported.');
            }

            $normalized[$flag->value] = $flag;
        }

        ksort($normalized);
        $this->enumClass = $enumClass;
        $this->flags = array_values($normalized);
    }

    /**
     * @template TFlag of BackedEnum
     * @param class-string<TFlag> $enumClass
     * @param TFlag ...$flags
     * @return self<TFlag>
     */
    public static function forEnum(string $enumClass, BackedEnum ...$flags): self
    {
        return new self($enumClass, $flags);
    }

    /**
     * @return self<Flag>
     */
    public static function fromFlags(Flag ...$flags): self
    {
        return new self(Flag::class, $flags);
    }

    /**
     * @template TFlag of BackedEnum
     * @param class-string<TFlag> $enumClass
     * @return self<TFlag>
     */
    public static function fromInt(int $mask, string $enumClass = Flag::class): self
    {
        if (!enum_exists($enumClass)) {
            throw new InvalidArgumentException(sprintf('Enum class %s does not exist.', $enumClass));
        }

        /** @var array<int, TFlag> $cases */
        $cases = $enumClass::cases();
        /** @var array<int, TFlag> $flags */
        $flags = [];
        foreach ($cases as $flag) {
            if (!is_int($flag->value)) {
                throw new InvalidArgumentException('Only int-backed enums are supported.');
            }

            if (($mask & $flag->value) !== 0) {
                $flags[] = $flag;
            }
        }

        return new self($enumClass, $flags);
    }

    /**
     * @return class-string<TEnum>
     */
    public function enumClass(): string
    {
        return $this->enumClass;
    }

    /**
     * @return array<int, TEnum>
     */
    public function flags(): array
    {
        return $this->flags;
    }

    public function toInt(): int
    {
        return array_reduce(
            $this->flags,
            fn(int $mask, BackedEnum $flag): int => $mask | (int) $flag->value,
            0,
        );
    }

    /**
     * @param TEnum $flag
     */
    public function has(BackedEnum $flag): bool
    {
        if (!$flag instanceof $this->enumClass) {
            return false;
        }

        return in_array($flag, $this->flags, true);
    }

    /**
     * @param TEnum ...$flags
     * @return self<TEnum>
     */
    public function add(BackedEnum ...$flags): self
    {
        return new self($this->enumClass, [...$this->flags, ...$flags]);
    }

    /**
     * @param TEnum ...$flags
     * @return self<TEnum>
     */
    public function remove(BackedEnum ...$flags): self
    {
        $removeMap = [];
        foreach ($flags as $flag) {
            if (!$flag instanceof $this->enumClass) {
                continue;
            }
            $removeMap[(int) $flag->value] = true;
        }

        return new self(
            $this->enumClass,
            array_values(
                array_filter(
                    $this->flags,
                    fn(BackedEnum $flag): bool => !isset($removeMap[(int) $flag->value]),
                ),
            ),
        );
    }

    public function count(): int
    {
        return count($this->flags);
    }

    /**
     * @return Traversable<int, TEnum>
     */
    public function getIterator(): Traversable
    {
        yield from $this->flags;
    }

    /**
     * @return array{mask:int, flags: array<int, string>}
     */
    public function jsonSerialize(): array
    {
        return [
            'mask' => $this->toInt(),
            'flags' => array_map(
                fn(BackedEnum $flag): string => $flag->name,
                $this->flags,
            ),
        ];
    }
}
