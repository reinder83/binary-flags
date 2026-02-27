<?php

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
 * @implements IteratorAggregate<int, BackedEnum>
 */
final class Mask implements Countable, IteratorAggregate, JsonSerializable
{
    /** @var class-string<BackedEnum> */
    private string $enumClass;

    /** @var array<int, BackedEnum> */
    private array $flags;

    /**
     * @param class-string<BackedEnum> $enumClass
     * @param array<int|string, BackedEnum> $flags
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
     * @param class-string<BackedEnum> $enumClass
     */
    public static function forEnum(string $enumClass, BackedEnum ...$flags): self
    {
        return new self($enumClass, $flags);
    }

    public static function fromFlags(Flag ...$flags): self
    {
        return new self(Flag::class, $flags);
    }

    /**
     * @param class-string<BackedEnum> $enumClass
     */
    public static function fromInt(int $mask, string $enumClass = Flag::class): self
    {
        if (!enum_exists($enumClass)) {
            throw new InvalidArgumentException(sprintf('Enum class %s does not exist.', $enumClass));
        }

        /** @var array<int, BackedEnum> $cases */
        $cases = $enumClass::cases();
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
     * @return class-string<BackedEnum>
     */
    public function enumClass(): string
    {
        return $this->enumClass;
    }

    /**
     * @return array<int, BackedEnum>
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

    public function has(BackedEnum $flag): bool
    {
        if (!$flag instanceof $this->enumClass) {
            return false;
        }

        return in_array($flag, $this->flags, true);
    }

    public function add(BackedEnum ...$flags): self
    {
        return new self($this->enumClass, [...$this->flags, ...$flags]);
    }

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
