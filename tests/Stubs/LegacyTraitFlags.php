<?php

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Countable;
use Iterator;
use JsonSerializable;
use Reinder83\BinaryFlags\Traits\BinaryFlags;

class LegacyTraitFlags implements Countable, Iterator, JsonSerializable
{
    use BinaryFlags;

    private int $currentPos = 0;

    public function current(): string
    {
        /** @var string $result */
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

    public function jsonSerialize(): array
    {
        return ['mask' => $this->mask];
    }
}
