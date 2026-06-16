<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\BinaryEnumFlags;

/**
 * @extends BinaryEnumFlags<Permission>
 */
class ExamplePermissionFlags extends BinaryEnumFlags
{
    protected static function getFlagEnumClass(): string
    {
        return Permission::class;
    }
}
