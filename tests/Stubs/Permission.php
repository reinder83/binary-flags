<?php

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\Bits;

enum Permission: int
{
    case CanView = Bits::BIT_1;
    case CanBook = Bits::BIT_2;
    case CanCancel = Bits::BIT_3;
    case CanRefund = Bits::BIT_4;
}
