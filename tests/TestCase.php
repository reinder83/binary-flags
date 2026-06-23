<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags\Tests;

use Closure;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Reinder83\BinaryFlags\Tests\Stubs\ExampleFlags;

abstract class TestCase extends BaseTestCase
{
    protected ExampleFlags $test;

    /** @var Closure(ExampleFlags): void */
    protected Closure $callback;

    protected int $mask = 0;
}
