<?php

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\BinaryEnumFlags;
use Reinder83\BinaryFlags\Bits;
use Reinder83\BinaryFlags\Flag;

/**
 * @extends BinaryEnumFlags<Flag>
 */
class ExampleEnumFlags extends BinaryEnumFlags
{
    public const FOO = Bits::BIT_1;
    public const BAR = Bits::BIT_2;
    public const BAZ = Bits::BIT_3;
    public const QUX = Bits::BIT_4;

    protected static function getFlagEnumClass(): string
    {
        return Flag::class;
    }
}
