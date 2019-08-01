<?php

namespace Reinder83\BinaryFlags;

use Countable;
use Iterator;
use JsonSerializable;

/**
 * This class holds useful methods for checking, adding or removing binary flags
 *
 * @author Reinder
 */
abstract class BinaryFlags implements Iterator, Countable, JsonSerializable
{
    use Traits\BinaryFlags;

    /**
     * @var int
     */
    private $currentPos = 0;

    /**
     * Initiate class
     * @param int [$mask = 0]
     * @param callable [$onModify = null]
     */
    public function __construct($mask = 0, callable $onModify = null)
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
    public function current()
    {
        return $this->getFlagNames($this->currentPos);
    }

    /**
     * Move forward to next element
     *
     * @return void
     * @since 1.2.0
     */
    public function next()
    {
        $this->currentPos <<= 1; // shift to next bit
        while (($this->mask & $this->currentPos) == 0 && $this->currentPos > 0) {
            $this->currentPos <<= 1;
        }
    }

    /**
     * Return the key of the current element
     *
     * @return int the flag
     * @since 1.2.0
     */
    public function key()
    {
        return $this->currentPos;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean Returns true on success or false on failure.
     * @since 1.2.0
     */
    public function valid()
    {
        return $this->currentPos > 0;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     * @since 1.2.0
     */
    public function rewind()
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
    public function count()
    {
        $count = 0;
        $mask  = $this->mask;

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
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * @since 1.2.0
     */
    public function jsonSerialize()
    {
        return ['mask' => $this->mask];
    }
}
