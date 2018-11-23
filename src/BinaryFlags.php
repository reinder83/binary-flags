<?php

namespace Reinder83\BinaryFlags;

/**
 * This class holds useful methods for checking, adding or removing binary flags
 *
 * @author Reinder
 */
abstract class BinaryFlags
{
    use Traits\HasBinaryFlags;

    public static function make(...$flags)
    {
        $binaryFlags = new static();
        $binaryFlags->addFlag(...$flags);

        return $binaryFlags;
    }
}
