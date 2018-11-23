<?php

namespace Reinder83\BinaryFlags\Tests;

use Reinder83\BinaryFlags\Bits;
use Reinder83\BinaryFlags\Flag;

class FlagHelperTest extends TestCase
{
    // test base mask set in setUp
    public function testAddingFlags()
    {
        $this->assertEquals(0x0, Flag::add(0x0));
        $this->assertEquals(0x1, Flag::add(0x0, Bits::BIT_1));
        $this->assertEquals(0x2, Flag::add(0x0, Bits::BIT_2));
        $this->assertEquals(0x3, Flag::add(0x0, Bits::BIT_1 , Bits::BIT_2));
        $this->assertEquals(0x3, Flag::add(0x0, Bits::BIT_1 | Bits::BIT_2 | Bits::BIT_2));
        $this->assertNotEquals(0x4, Flag::add(0x0, [Bits::BIT_2 , Bits::BIT_2]));
    }

    // test base mask set in setUp
    public function testRemovingFlags()
    {
        $this->assertEquals(0x0, Flag::remove(0x0));
        $this->assertEquals(0x0, Flag::remove(0x1, Bits::BIT_1));
        $this->assertEquals(0x1, Flag::remove(0x3, Bits::BIT_2));
        $this->assertEquals(0x10, Flag::remove(0x13, Bits::BIT_1 | Bits::BIT_2));
        $this->assertEquals(0x10, Flag::remove(0x12, Bits::BIT_1, Bits::BIT_2, Bits::BIT_2));
        $this->assertNotEquals(0x2, Flag::remove(0x6, [Bits::BIT_2, Bits::BIT_2]));
    }


    // test base mask set in setUp
    public function testHasFlags()
    {
        $this->assertTrue(Flag::has(0x0));
        $this->assertTrue(Flag::has(0x1, Bits::BIT_1));
        $this->assertTrue(Flag::has(0x3, Bits::BIT_2));
        $this->assertTrue(Flag::has(0x13, Bits::BIT_1 | Bits::BIT_2));
        $this->assertTrue(Flag::has(0x13, Bits::BIT_1, Bits::BIT_2, Bits::BIT_2));
        $this->assertFalse(Flag::has(0x3, Bits::BIT_2, Bits::BIT_3));
        $this->assertFalse(Flag::has(0x5, [Bits::BIT_2, Bits::BIT_2]));
    }

    // test base mask set in setUp
    public function testHasAnyFlags()
    {
        $this->assertFalse(Flag::hasAny(0x0));
        $this->assertTrue(Flag::hasAny(0x1, Bits::BIT_1, Bits::BIT_2));
        $this->assertTrue(Flag::hasAny(0x3, Bits::BIT_2, Bits::BIT_3));
        $this->assertTrue(Flag::hasAny(0x13, Bits::BIT_1 | Bits::BIT_2));
        $this->assertTrue(Flag::hasAny(0x13, Bits::BIT_1, Bits::BIT_2, Bits::BIT_2));
        $this->assertFalse(Flag::hasAny(0x13, Bits::BIT_10, Bits::BIT_20, Bits::BIT_22));
        $this->assertFalse(Flag::hasAny(0x5, [Bits::BIT_2, Bits::BIT_2]));
    }
}