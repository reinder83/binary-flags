<?php

namespace Reinder83\BinaryFlags\Tests;

use Reinder83\BinaryFlags\Bits;
use Reinder83\BinaryFlags\BinaryFlags;

/**
 * Simple Example class that only defines constants
 *
 * @package Reinder83\BinaryFlags
 */
class ExampleFlags extends BinaryFlags
{
    const FOO = Bits::BIT_1;
    const BAR = Bits::BIT_2;
    const BAZ = Bits::BIT_3;
    const QUX = Bits::BIT_4;
}