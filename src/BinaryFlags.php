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
}
