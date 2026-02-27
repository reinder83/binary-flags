<?php

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\BinaryEnumFlags;

class ExamplePermissionFlags extends BinaryEnumFlags
{
    protected static function getFlagEnumClass(): string
    {
        return Permission::class;
    }
}
