<?php namespace Reinder83\BinaryFlags\Tests;

class BinaryFlagsTest extends \PHPUnit_Framework_TestCase
{
    public function testAddFlag()
    {
        // create new class without mask
        $test = new ExampleFlags();

        // no flags should be set
        $this->assertFalse($test->checkFlag(ExampleFlags::FOO));
        $this->assertFalse($test->checkFlag(ExampleFlags::BAR));
        $this->assertFalse($test->checkFlag(ExampleFlags::BAZ));

        // add a flag
        $test->addFlag(ExampleFlags::FOO);

        // verify the correct flag is set
        $this->assertTrue($test->checkFlag(ExampleFlags::FOO));
        $this->assertFalse($test->checkFlag(ExampleFlags::BAR));
        $this->assertFalse($test->checkFlag(ExampleFlags::BAZ));

        // add the flag again
        $test->addFlag(ExampleFlags::FOO);

        // verify the correct flag is set
        $this->assertTrue($test->checkFlag(ExampleFlags::FOO));
        $this->assertFalse($test->checkFlag(ExampleFlags::BAR));
        $this->assertFalse($test->checkFlag(ExampleFlags::BAZ));
    }
    
}