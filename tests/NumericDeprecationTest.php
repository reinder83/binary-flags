<?php

use Reinder83\BinaryFlags\Tests\Stubs\ExampleFlags;

beforeEach(function (): void {
    $this->test = new ExampleFlags(ExampleFlags::FOO | ExampleFlags::BAR);
});

test('float mask and flag values are deprecated in v2 and still work', function (): void {
    $messages = [];
    set_error_handler(function (int $severity, string $message) use (&$messages): bool {
        if ($severity === E_USER_DEPRECATED) {
            $messages[] = $message;

            return true;
        }

        return false;
    });

    try {
        new ExampleFlags((float) ExampleFlags::FOO);
        $this->test->setMask((float) ExampleFlags::FOO);
        $this->test->addFlag((float) ExampleFlags::BAR);
        $this->test->removeFlag((float) ExampleFlags::FOO);
        $this->test->checkFlag((float) ExampleFlags::BAR);
        $this->test->checkAnyFlag((float) ExampleFlags::BAR);
        $this->test->getFlagNames((float) ExampleFlags::BAR);
    } finally {
        restore_error_handler();
    }

    expect($messages)->toHaveCount(7)
        ->and($this->test->checkFlag(ExampleFlags::BAR))->toBeTrue()
        ->and($this->test->checkFlag(ExampleFlags::FOO))->toBeFalse();
});
