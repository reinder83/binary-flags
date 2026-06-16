<?php

declare(strict_types=1);

use Reinder83\BinaryFlags\Mask;
use Reinder83\BinaryFlags\Tests\Stubs\ExamplePermissionFlags;
use Reinder83\BinaryFlags\Tests\Stubs\Permission;

test('enum flags expose readable names from enum case names', function (): void {
    $flags = new ExamplePermissionFlags(Permission::CanView);
    $flags->addFlag(Permission::CanBook);
    $flags->addFlag(Mask::forEnum(Permission::class, Permission::CanCancel));

    expect($flags->getFlagNames())->toEqual('Can View, Can Book, Can Cancel')
        ->and($flags->getFlagNames(Permission::CanBook))->toEqual('Can Book')
        ->and($flags->getFlagNames(null, true))->toEqual([
            Permission::CanView->value => 'Can View',
            Permission::CanBook->value => 'Can Book',
            Permission::CanCancel->value => 'Can Cancel',
        ]);
});
