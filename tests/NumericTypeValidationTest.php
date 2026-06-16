<?php

declare(strict_types=1);

use Reinder83\BinaryFlags\Tests\Stubs\ExampleFlags;

beforeEach(function (): void {
    $this->test = new ExampleFlags(ExampleFlags::FOO | ExampleFlags::BAR);
});

test('float mask and flag values are rejected in v3', function (): void {
    expect(fn() => new ExampleFlags((float) ExampleFlags::FOO))
        ->toThrow(TypeError::class);

    expect(fn() => $this->test->setMask((float) ExampleFlags::FOO))
        ->toThrow(TypeError::class);

    expect(fn() => $this->test->addFlag((float) ExampleFlags::BAR))
        ->toThrow(TypeError::class);

    expect(fn() => $this->test->removeFlag((float) ExampleFlags::FOO))
        ->toThrow(TypeError::class);

    expect(fn() => $this->test->checkFlag((float) ExampleFlags::BAR))
        ->toThrow(TypeError::class);

    expect(fn() => $this->test->checkAnyFlag((float) ExampleFlags::BAR))
        ->toThrow(TypeError::class);

    expect(fn() => $this->test->getFlagNames((float) ExampleFlags::BAR))
        ->toThrow(TypeError::class);
});

test('bit 64 is no longer defined in v3', function (): void {
    expect(defined('Reinder83\\BinaryFlags\\Bits::BIT_64'))->toBeFalse();
});
