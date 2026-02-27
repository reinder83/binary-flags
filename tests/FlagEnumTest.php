<?php

use Reinder83\BinaryFlags\Flag;
use Reinder83\BinaryFlags\Mask;
use Reinder83\BinaryFlags\Tests\Stubs\ExampleEnumFlags;

test('enum flags are supported', function (): void {
    $flags = new ExampleEnumFlags(Flag::Flag1);
    $flags->addFlag(Flag::Flag2);
    $flags->addFlag(Flag::mask(Flag::Flag3, Flag::Flag4));
    $flags->removeFlag(Flag::Flag1);

    expect($flags->checkFlag(Flag::Flag2))->toBeTrue()
        ->and($flags->checkFlag(Flag::Flag1))->toBeFalse()
        ->and($flags->checkAnyFlag(Flag::mask(Flag::Flag1, Flag::Flag3)))->toBeTrue()
        ->and($flags->getMask())->toEqual(Flag::mask(Flag::Flag2, Flag::Flag3, Flag::Flag4)->toInt())
        ->and($flags->getFlagNames(Flag::Flag4))->toEqual('Flag4');
});

test('mask value object stores flags and converts to int', function (): void {
    $mask = Flag::mask(Flag::Flag1, Flag::Flag3, Flag::Flag3);

    expect($mask)->toBeInstanceOf(Mask::class)
        ->and($mask->count())->toEqual(2)
        ->and($mask->has(Flag::Flag1))->toBeTrue()
        ->and($mask->has(Flag::Flag2))->toBeFalse()
        ->and($mask->toInt())->toEqual(Flag::Flag1->value | Flag::Flag3->value)
        ->and($mask->flags())->toEqual([Flag::Flag1, Flag::Flag3]);
});

test('enum flags accept mask value object', function (): void {
    $flags = new ExampleEnumFlags(Flag::mask(Flag::Flag1, Flag::Flag2));
    $flags->addFlag(Flag::mask(Flag::Flag3, Flag::Flag4));
    $flags->removeFlag(Mask::fromInt(Flag::Flag1->value | Flag::Flag4->value));

    expect($flags->checkAnyFlag(Flag::mask(Flag::Flag1, Flag::Flag2)))->toBeTrue()
        ->and($flags->checkFlag(Mask::fromInt(Flag::Flag2->value | Flag::Flag3->value)))->toBeTrue()
        ->and($flags->checkFlag(Flag::Flag1))->toBeFalse()
        ->and($flags->checkFlag(Flag::Flag4))->toBeFalse()
        ->and($flags->getMask())->toEqual(Flag::mask(Flag::Flag2, Flag::Flag3)->toInt());
});
