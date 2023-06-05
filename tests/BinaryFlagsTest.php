<?php

namespace Reinder83\BinaryFlags\Tests;

use PHPUnit\Framework\TestCase;

class BinaryFlagsTest extends TestCase
{
    /**
     * @var ExampleFlags
     */
    protected ExampleFlags $test;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var int
     */
    protected int $mask = 0x0;

    /**
     * public method to set the mask
     *
     * @param int $mask
     */
    public function setMask(int $mask): void
    {
        $this->mask = $mask;
    }

    // set up test case
    public function setUp(): void
    {
        // base mask
        $this->mask = ExampleFlags::FOO | ExampleFlags::BAR;

        // callback function
        $model          = $this;
        $this->callback = function (ExampleFlags $flags) use ($model) {
            $model->setMask($flags->getMask());
        };

        // create new class with FOO and BAR set
        $this->test = new ExampleFlags($this->mask, $this->callback);
    }

    // test base mask set in setUp
    public function testBaseMask(): void
    {
        // verify if the correct flags are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::FOO));
        $this->assertTrue($this->test->checkFlag(ExampleFlags::BAR));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAZ));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::QUX));

        // flag 1 and 2 should be resulting in 3
        $this->assertEquals(0x3, $this->test->getMask());
    }

    // test check flags with multiple flags
    public function testMultipleFlags(): void
    {
        // test if all are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::FOO | ExampleFlags::BAR));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAR | ExampleFlags::BAZ));

        // test if any are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::BAR | ExampleFlags::BAZ, false));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAZ | ExampleFlags::QUX, false));

        // test if any are set
        $this->assertTrue($this->test->checkAnyFlag(ExampleFlags::BAR | ExampleFlags::BAZ));
        $this->assertFalse($this->test->checkAnyFlag(ExampleFlags::BAZ | ExampleFlags::QUX));
    }

    // test the callback method
    public function testCallback(): void
    {
        // add BAZ which result in mask = 7
        $this->test->addFlag(ExampleFlags::BAZ);

        // the callback method should set the mask in this class to 7
        $this->assertEquals(0x7, $this->mask);
    }

    // test adding a flag
    public function testAddFlag(): void
    {
        // add a flag
        $this->test->addFlag(ExampleFlags::BAZ);

        // add an existing flag
        $this->test->addFlag(ExampleFlags::FOO);

        // verify if the correct flags are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::FOO));
        $this->assertTrue($this->test->checkFlag(ExampleFlags::BAR));
        $this->assertTrue($this->test->checkFlag(ExampleFlags::BAZ));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::QUX));
    }

    // test removing a flag
    public function testRemoveFlag(): void
    {
        // remove a flag
        $this->test->removeFlag(ExampleFlags::BAR);

        // remove an non-existing flag
        $this->test->removeFlag(ExampleFlags::BAZ);

        // verify if the correct flags are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::FOO));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAR));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAZ));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::QUX));
    }

    public function testFlagNames(): void
    {
        $this->assertEquals('Foo, Bar', $this->test->getFlagNames());

        $this->assertEquals('Baz', $this->test->getFlagNames(ExampleFlags::BAZ));

        $this->assertEquals([
            ExampleFlags::FOO => 'Foo',
            ExampleFlags::BAR => 'Bar',
        ], $this->test->getFlagNames(null, true));
    }

    public function testNamedFlagNames(): void
    {
        // same mask as exampleFlags
        $named = new ExampleFlagsWithNames($this->test->getMask());

        $this->assertEquals('My foo description, My bar description', $named->getFlagNames());
    }

    public function testGetAllFlagsMask(): void
    {
        $this->assertEquals(1 + 2 + 4 + 8, ExampleFlags::getAllFlagsMask());
    }

    public function testCountable(): void
    {
        $this->assertEquals(2, $this->test->count());
    }

    public function testIterable(): void
    {
        $test          = new ExampleFlagsWithNames(ExampleFlags::FOO | ExampleFlags::BAZ);
        $expectedFlags = $test->getFlagNames(ExampleFlags::FOO | ExampleFlags::BAZ, true);

        $result = [];
        foreach ($test as $flag => $description) {
            $result[$flag] = $description;
        }

        $this->assertEquals($expectedFlags, $result);
    }

    public function testJsonSerializable(): void
    {
        $this->assertEquals(
            sprintf('{"mask":%d}', $this->test->getMask()),
            json_encode($this->test)
        );
    }
}
