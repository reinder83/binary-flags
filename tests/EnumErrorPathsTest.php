<?php

use Reinder83\BinaryFlags\Flag;
use Reinder83\BinaryFlags\Tests\Stubs\ExamplePermissionFlags;
use Reinder83\BinaryFlags\Tests\Stubs\Permission;

test('enum flags reject wrong enum instances', function (): void {
    $flags = new ExamplePermissionFlags(Permission::CanView);

    expect(fn() => $flags->setMask(Flag::Flag1))
        ->toThrow(InvalidArgumentException::class);

    expect(fn() => $flags->addFlag(Flag::Flag2))
        ->toThrow(InvalidArgumentException::class);
});

test('enum flags reject masks from a different enum type', function (): void {
    $flags = new ExamplePermissionFlags(Permission::CanView);
    $wrongMask = Flag::mask(Flag::Flag1, Flag::Flag2);

    expect(fn() => $flags->checkAnyFlag($wrongMask))
        ->toThrow(InvalidArgumentException::class);
});
