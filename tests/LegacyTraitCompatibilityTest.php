<?php

declare(strict_types=1);

use Reinder83\BinaryFlags\Tests\Stubs\LegacyTraitFlags;

test('legacy binaryflags trait alias remains usable', function (): void {
    $flags = new LegacyTraitFlags();
    $flags->setMask(3);

    expect($flags->getMask())->toEqual(3)
        ->and($flags->checkFlag(1))->toBeTrue()
        ->and($flags->checkFlag(4))->toBeFalse();
});
