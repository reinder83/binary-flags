<?php

use Reinder83\BinaryFlags\Mask;
use Reinder83\BinaryFlags\Tests\Stubs\ExamplePermissionFlags;
use Reinder83\BinaryFlags\Tests\Stubs\Permission;

test('custom enum classes are supported in binary enum flags', function (): void {
    $flags = new ExamplePermissionFlags(Permission::CanView);
    $flags->addFlag(Permission::CanBook);
    $flags->addFlag(Mask::forEnum(Permission::class, Permission::CanCancel));

    expect($flags->checkFlag(Permission::CanBook))->toBeTrue()
        ->and($flags->checkAnyFlag(Mask::forEnum(Permission::class, Permission::CanView, Permission::CanRefund)))->toBeTrue()
        ->and($flags->getMask())->toBeInstanceOf(Mask::class)
        ->and($flags->getMask()->toInt())->toEqual(Permission::CanView->value | Permission::CanBook->value | Permission::CanCancel->value);
});

test('enum flags do not trigger float deprecation warnings', function (): void {
    $messages = [];
    set_error_handler(function (int $severity, string $message) use (&$messages): bool {
        if ($severity === E_USER_DEPRECATED) {
            $messages[] = $message;

            return true;
        }

        return false;
    });

    try {
        $flags = new ExamplePermissionFlags(Permission::CanView);
        $flags->setMask(Permission::CanBook);
        $flags->addFlag(Permission::CanCancel);
        $flags->removeFlag(Permission::CanBook);
        $flags->checkFlag(Permission::CanCancel);
        $flags->checkAnyFlag(Permission::CanCancel);
        $flags->getFlagNames(Permission::CanCancel);
    } finally {
        restore_error_handler();
    }

    expect($messages)->toBeEmpty();
});

test('iterating enum flags yields enum cases', function (): void {
    $flags = new ExamplePermissionFlags(Mask::forEnum(Permission::class, Permission::CanView, Permission::CanCancel));

    $result = [];
    foreach ($flags as $bit => $permission) {
        $result[$bit] = $permission;
    }

    expect($result)->toEqual([
        Permission::CanView->value => Permission::CanView,
        Permission::CanCancel->value => Permission::CanCancel,
    ]);
});
