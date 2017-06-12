<?php

namespace Reinder83\BinaryFlags\Tests;

class BinaryFlagsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExampleFlags
     */
    protected $test;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var int
     */
    protected $mask = 0x0;

    /**
     * public method to set the mask
     * @param $mask
     */
    public function setMask($mask)
    {
        $this->mask = $mask;
    }

    // set up test case
    public function setUp()
    {
        // base mask
        $this->mask = ExampleFlags::FOO | ExampleFlags::BAR;

        // callback function
        $model = $this;
        $this->callback = function(ExampleFlags $flags) use ($model) {
            $model->setMask($flags->getMask());
        };

        // create new class with FOO and BAR set
        $this->test = new ExampleFlags($this->mask, $this->callback);
    }

    // test base mask set in setUp
    public function testBaseMask()
    {
        // verify if the correct flags are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::FOO));
        $this->assertTrue($this->test->checkFlag(ExampleFlags::BAR));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAZ));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::QUX));

        // flag 1 and 2 should be set resulting in 3
        $this->assertEquals(0x3, $this->test->getMask());
    }

    // test check flags with multiple flags
    public function testMultipleFlags()
    {
        // test if all are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::FOO | ExampleFlags::BAR));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAR | ExampleFlags::BAZ));

        // test if any are set
        $this->assertTrue($this->test->checkFlag(ExampleFlags::BAR | ExampleFlags::BAZ, false));
        $this->assertFalse($this->test->checkFlag(ExampleFlags::BAZ | ExampleFlags::QUX, false));
    }

    // test the callback method
    public function testCallback()
    {
        // add BAZ which result in mask = 7
        $this->test->addFlag(ExampleFlags::BAZ);

        // the callback method should set the mask in this class to 7
        $this->assertEquals(0x7, $this->mask);
    }

    // test adding a flag
    public function testAddFlag()
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
    public function testRemoveFlag()
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

    public function testFlagNames()
    {
        $this->assertEquals('Foo, Bar', $this->test->getFlagNames());

        $this->assertEquals('Baz', $this->test->getFlagNames(ExampleFlags::BAZ));

        $this->assertEquals(['Foo', 'Bar'], $this->test->getFlagNames(null, true));
    }

    public function testNamedFlagNames()
    {
        // same mask as exampleFlags
        $named = new ExampleFlagsWithNames($this->test->getMask());

        $this->assertEquals('My foo description, My bar description', $named->getFlagNames());
    }
}