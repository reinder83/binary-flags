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
 * @implements Iterator<int, string>
 */
abstract class BinaryEnumFlags implements Countable, Iterator, JsonSerializable
{
    use Traits\InteractsWithEnumFlags;

    private int $currentPos = 0;

    public function __construct(int|BackedEnum|Mask $mask = 0, ?Closure $onModify = null)
    {
        $this->setMask($mask);

        if ($onModify !== null) {
            $this->setOnModifyCallback($onModify);
        }
    }

    public function current(): string
    {
        /** @var string $result Will always be string since the second argument is false */
        $result = $this->getFlagNames($this->currentPos);

        return $result;
    }

    public function next(): void
    {
        $this->currentPos <<= 1;
        while (($this->mask & $this->currentPos) === 0 && $this->currentPos > 0) {
            $this->currentPos <<= 1;
        }
    }

    public function key(): int
    {
        return $this->currentPos;
    }

    public function valid(): bool
    {
        return $this->currentPos > 0;
    }

    public function rewind(): void
    {
        if ($this->mask === 0) {
            $this->currentPos = 0;

            return;
        }

        $this->currentPos = 1;
        while (($this->mask & $this->currentPos) === 0) {
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
