<?php

namespace Reinder83\BinaryFlags;

use Closure;
use Countable;
use Iterator;
use JsonSerializable;

/**
 * This class holds useful methods for checking, adding or removing binary flags
 *
 * @author Reinder
 *
 * @implements Iterator<int|float, string>
 */
abstract class BinaryFlags implements Iterator, Countable, JsonSerializable
{
    use Traits\BinaryFlags;

    /**
     * @var int
     */
    private int $currentPos = 0;

    /**
     * Initiate class
     * @param int|float    $mask
     * @param Closure|null $onModify
     */
    public function __construct(int|float $mask = 0, ?Closure $onModify = null)
    {
        $this->setMask($mask);

        // set onModify callback if specified
        if ($onModify !== null) {
            $this->setOnModifyCallback($onModify);
        }
    }

    /**
     * Return the current element
     *
     * @return string the description of the flag or the name of the constant
     * @since 1.2.0
     */
    public function current(): string
    {
        /** @var string $result Will always be string since the second argument is false */
        $result = $this->getFlagNames($this->currentPos, false);

        return $result;
    }

    /**
     * Move forward to next element
     *
     * @return void
     * @since 1.2.0
     */
    public function next(): void
    {
        $this->currentPos <<= 1; // shift to next bit
        while (($this->mask & $this->currentPos) == 0 && $this->currentPos > 0) {
            $this->currentPos <<= 1;
        }
    }

    /**
     * Return the key of the current element
     *
     * @return int|float the flag
     * @since 1.2.0
     */
    public function key(): int|float
    {
        return $this->currentPos;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean Returns true on success or false on failure.
     * @since 1.2.0
     */
    public function valid(): bool
    {
        return $this->currentPos > 0;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     * @since 1.2.0
     */
    public function rewind(): void
    {
        // find the first element
        if ($this->mask === 0) {
            $this->currentPos = 0;

            return;
        }

        $this->currentPos = 1;
        while (($this->mask & $this->currentPos) == 0) {
            $this->currentPos <<= 1;
        }
    }

    /**
     * Returns the number of flags that are set
     *
     * @return int
     *
     * The return value is cast to an integer.
     * @since 1.2.0
     */
    public function count(): int
    {
        $count = 0;
        $mask = $this->mask;

        while ($mask != 0) {
            if (($mask & 1) == 1) {
                $count++;
            }
            $mask >>= 1;
        }

        return $count;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array{mask: int|float} data which can be serialized by <b>json_encode</b>,
     * @since 1.2.0
     */
    public function jsonSerialize(): array
    {
        return ['mask' => $this->mask];
    }
}
