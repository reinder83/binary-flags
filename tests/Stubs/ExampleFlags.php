<?php

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\BinaryFlags;
use Reinder83\BinaryFlags\Bits;

class ExampleFlags extends BinaryFlags
{
    const FOO = Bits::BIT_1;
    const BAR = Bits::BIT_2;
    const BAZ = Bits::BIT_3;
    const QUX = Bits::BIT_4;
}