<?php

use Reinder83\BinaryFlags\Tests\Stubs\ExampleFlags;
use Reinder83\BinaryFlags\Tests\Stubs\ExampleFlagsWithNames;

beforeEach(function (): void {
    $this->mask = ExampleFlags::FOO | ExampleFlags::BAR;
    $this->callback = function (ExampleFlags $flags): void {
        $this->mask = $flags->getMask();
    };
    $this->test = new ExampleFlags($this->mask, $this->callback);
});

test('base mask', function (): void {
    expect($this->test->checkFlag(ExampleFlags::FOO))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::BAR))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::BAZ))->toBeFalse()
        ->and($this->test->checkFlag(ExampleFlags::QUX))->toBeFalse()
        ->and($this->test->getMask())->toEqual(0x3);
});

test('multiple flags', function (): void {
    expect($this->test->checkFlag(ExampleFlags::FOO | ExampleFlags::BAR))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::BAR | ExampleFlags::BAZ))->toBeFalse()
        ->and($this->test->checkFlag(ExampleFlags::BAR | ExampleFlags::BAZ, false))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::BAZ | ExampleFlags::QUX, false))->toBeFalse()
        ->and($this->test->checkAnyFlag(ExampleFlags::BAR | ExampleFlags::BAZ))->toBeTrue()
        ->and($this->test->checkAnyFlag(ExampleFlags::BAZ | ExampleFlags::QUX))->toBeFalse();
});

test('callback', function (): void {
    $this->test->addFlag(ExampleFlags::BAZ);

    expect($this->mask)->toEqual(0x7);
});

test('add flag', function (): void {
    $this->test->addFlag(ExampleFlags::BAZ);
    $this->test->addFlag(ExampleFlags::FOO);

    expect($this->test->checkFlag(ExampleFlags::FOO))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::BAR))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::BAZ))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::QUX))->toBeFalse();
});

test('remove flag', function (): void {
    $this->test->removeFlag(ExampleFlags::BAR);
    $this->test->removeFlag(ExampleFlags::BAZ);

    expect($this->test->checkFlag(ExampleFlags::FOO))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::BAR))->toBeFalse()
        ->and($this->test->checkFlag(ExampleFlags::BAZ))->toBeFalse()
        ->and($this->test->checkFlag(ExampleFlags::QUX))->toBeFalse();
});

test('get all flags mask', function (): void {
    expect(ExampleFlags::getAllFlagsMask())->toEqual(1 + 2 + 4 + 8);
});

test('countable', function (): void {
    expect($this->test->count())->toEqual(2);
});

test('iterable', function (): void {
    $test = new ExampleFlagsWithNames(ExampleFlagsWithNames::FOO | ExampleFlagsWithNames::BAZ);
    $expectedFlags = $test->getFlagNames(ExampleFlagsWithNames::FOO | ExampleFlagsWithNames::BAZ, true);

    $result = [];
    foreach ($test as $flag => $description) {
        $result[$flag] = $description;
    }

    expect($result)->toEqual($expectedFlags);
});

test('json serializable', function (): void {
    expect(json_encode($this->test))->toEqual(sprintf('{"mask":%d}', $this->test->getMask()));
});
