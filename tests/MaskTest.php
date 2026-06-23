<?php

declare(strict_types=1);

use Reinder83\BinaryFlags\Flag;
use Reinder83\BinaryFlags\Mask;
use Reinder83\BinaryFlags\Tests\Stubs\BadStringFlag;
use Reinder83\BinaryFlags\Tests\Stubs\Permission;

test('mask add and remove are immutable operations', function (): void {
    $base = Mask::forEnum(Permission::class, Permission::CanView);
    $added = $base->add(Permission::CanBook);
    $removed = $added->remove(Permission::CanView);

    expect($base->flags())->toEqual([Permission::CanView])
        ->and($added->flags())->toEqual([Permission::CanView, Permission::CanBook])
        ->and($removed->flags())->toEqual([Permission::CanBook]);
});

test('mask exposes enum class and iterator', function (): void {
    $mask = Flag::mask(Flag::Flag1, Flag::Flag3);
    $iterated = [];

    foreach ($mask as $flag) {
        $iterated[] = $flag;
    }

    expect($mask->enumClass())->toEqual(Flag::class)
        ->and($iterated)->toEqual([Flag::Flag1, Flag::Flag3]);
});

test('mask json serialization includes mask and flag names', function (): void {
    $mask = Mask::forEnum(Permission::class, Permission::CanView, Permission::CanCancel);

    expect($mask->jsonSerialize())->toEqual([
        'mask' => Permission::CanView->value | Permission::CanCancel->value,
        'flags' => ['CanView', 'CanCancel'],
    ]);
});

test('mask from int throws when enum class does not exist', function (): void {
    expect(fn() => Mask::fromInt(1, 'Not\\A\\Real\\Enum'))
        ->toThrow(InvalidArgumentException::class);
});

test('mask for enum throws on mismatched enum instance', function (): void {
    expect(fn() => Mask::forEnum(Permission::class, Flag::Flag1))
        ->toThrow(InvalidArgumentException::class);
});

test('mask from int rejects non int backed enums', function (): void {
    expect(fn() => Mask::fromInt(1, BadStringFlag::class))
        ->toThrow(InvalidArgumentException::class);
});
