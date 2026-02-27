<?php

namespace Reinder83\BinaryFlags;

use BackedEnum;
use Closure;
use Countable;
use Iterator;
use JsonSerializable;

/**
 * Enum-aware variant of BinaryFlags.
 *
 * @template TEnum of BackedEnum
 * @implements Iterator<int, TEnum>
 */
abstract class BinaryEnumFlags implements Countable, Iterator, JsonSerializable
{
    /** @use Traits\InteractsWithEnumFlags<TEnum> */
    use Traits\InteractsWithEnumFlags;

    private int $currentPos = 0;

    /**
     * @param int|TEnum|Mask<TEnum> $mask
     */
    public function __construct(int|BackedEnum|Mask $mask = 0, ?Closure $onModify = null)
    {
        $this->setMask($mask);

        if ($onModify !== null) {
            $this->setOnModifyCallback($onModify);
        }
    }

    private function iterableMask(): int
    {
        return $this->mask & static::getAllFlagsMask();
    }

    /**
     * @return TEnum
     */
    public function current(): BackedEnum
    {
        $enumClass = static::getFlagEnumClass();

        /** @var TEnum $enum */
        $enum = $enumClass::from($this->currentPos);

        return $enum;
    }

    public function next(): void
    {
        $iterableMask = $this->iterableMask();
        $this->currentPos <<= 1;
        while (($iterableMask & $this->currentPos) === 0 && $this->currentPos > 0) {
            $this->currentPos <<= 1;
        }
    }

    public function key(): int
    {
        return $this->currentPos;
    }

    public function valid(): bool
    {
        return $this->currentPos > 0 && ($this->iterableMask() & $this->currentPos) !== 0;
    }

    public function rewind(): void
    {
        $iterableMask = $this->iterableMask();
        if ($iterableMask === 0) {
            $this->currentPos = 0;

            return;
        }

        $this->currentPos = 1;
        while (($iterableMask & $this->currentPos) === 0) {
            $this->currentPos <<= 1;
        }
    }

    public function count(): int
    {
        $count = 0;
        $mask = $this->mask;

        while ($mask !== 0) {
            if (($mask & 1) === 1) {
                $count++;
            }
            $mask >>= 1;
        }

        return $count;
    }

    /**
     * @return array{mask: int}
     */
    public function jsonSerialize(): array
    {
        return ['mask' => $this->mask];
    }
}
