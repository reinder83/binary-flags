<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\Bits;

enum InvalidPermission: int
{
    case CanView = Bits::BIT_1;
    case InvalidCombined = Bits::BIT_1 | Bits::BIT_2;
}
