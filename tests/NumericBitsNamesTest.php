<?php

use Reinder83\BinaryFlags\Tests\Stubs\ExampleFlags;
use Reinder83\BinaryFlags\Tests\Stubs\ExampleFlagsWithNames;

beforeEach(function (): void {
    $this->test = new ExampleFlags(ExampleFlags::FOO | ExampleFlags::BAR);
});

test('flag names', function (): void {
    expect($this->test->getFlagNames())->toEqual('Foo, Bar')
        ->and($this->test->getFlagNames(ExampleFlags::BAZ))->toEqual('Baz')
        ->and($this->test->getFlagNames(null, true))->toEqual([
            ExampleFlags::FOO => 'Foo',
            ExampleFlags::BAR => 'Bar',
        ]);
});

test('named flag names', function (): void {
    $named = new ExampleFlagsWithNames($this->test->getMask());

    expect($named->getFlagNames())->toEqual('My foo description, My bar description');
});
