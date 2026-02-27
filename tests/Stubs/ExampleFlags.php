<?php

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\BinaryFlags;
use Reinder83\BinaryFlags\Bits;

class ExampleFlags extends BinaryFlags
{
    public const FOO = Bits::BIT_1;
    public const BAR = Bits::BIT_2;
    public const BAZ = Bits::BIT_3;
    public const QUX = Bits::BIT_4;
}
