<?php

namespace Reinder83\BinaryFlags\Tests;

use Reinder83\BinaryFlags\Bits;
use Reinder83\BinaryFlags\BinaryFlags;

/**
 * Simple example class that defines constants and their descriptions
 *
 * @package Reinder83\BinaryFlags
 */
class ExampleFlagsWithNames extends BinaryFlags
{
    const FOO = Bits::BIT_1;
    const BAR = Bits::BIT_2;
    const BAZ = Bits::BIT_3;
    const QUX = Bits::BIT_4;

    public static function getAllFlags()
    {
        return [
            static::FOO => 'My foo description',
            static::BAR => 'My bar description',
            static::BAZ => 'My baz description',
            static::QUX => 'My qux description',
        ];
    }

}