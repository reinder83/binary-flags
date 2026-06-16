<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags\Tests\Stubs;

use Reinder83\BinaryFlags\BinaryEnumFlags;

/**
 * @extends BinaryEnumFlags<InvalidPermission>
 */
class ExampleInvalidPermissionFlags extends BinaryEnumFlags
{
    protected static function getFlagEnumClass(): string
    {
        return InvalidPermission::class;
    }
}
