<?php

namespace Reinder83\BinaryFlags\Tests;

use Reinder83\BinaryFlags\Bits;
use Reinder83\BinaryFlags\Flag;

class BinaryFlagsTest extends TestCase
{
    public function testNamedConstructor()
    {
        $mask = Bits::BIT_1 | Bits::BIT_2;

        $flags = ExampleFlags::make($mask);

        $this->assertEquals($mask, $flags->getMask());
    }

    public function testEventListener()
    {
        $flags = new ExampleFlags();

        $flags->setMask(0x0);

        // When BIT_2 is given we remove BIT_1
        $flags->addBeforeEventListener(function($new, $old) {
            if(Flag::has($new, Bits::BIT_2)) {
                return Flag::remove($new, Bits::BIT_1);
            } elseif(Flag::has($new, Bits::BIT_1)) {
                return Flag::remove($new, Bits::BIT_2);
            }
        });

        $flags->addBeforeEventListener(function($new, $old) {
            if(Flag::hasAny($new, Bits::BIT_3, Bits::BIT_4)) {
                return false;
            }
        });

        $flags->addFlag(Bits::BIT_1 | Bits::BIT_2);

        $this->assertEquals(Bits::BIT_2, $flags->getMask());

        $flags->addFlag([Bits::BIT_3, Bits::BIT_5]);

        $this->assertEquals(Bits::BIT_2, $flags->getMask());
    }

    public function testFlagNames()
    {
        $flags = ExampleFlagsWithNames::make([
            ExampleFlagsWithNames::BAR,
            ExampleFlagsWithNames::FOO
        ]);

        $this->assertEquals([
            ExampleFlagsWithNames::FOO => 'My foo description',
            ExampleFlagsWithNames::BAR => 'My bar description',
        ], $flags->getFlags());

        $flags->setCustomFlagNames($custom = [
            ExampleFlagsWithNames::FOO => 'My new foo description',
            ExampleFlagsWithNames::BAR => 'My new bar description',
        ]);

        $this->assertEquals($custom, $flags->getFlags());
    }

    public function testLaravelIntegration()
    {
        // Testing empty model
        $model = ModelFlags::newInstance();

        $this->assertFalse(isset($model->attributes[$model->flagsColumn()]));

        $model->addFlag(ModelFlags::FOO, ModelFlags::BAR);

        $this->assertTrue(isset($model->attributes[$model->flagsColumn()]));
        $this->assertEquals(ModelFlags::FOO | ModelFlags::BAR, $model->attributes[$model->flagsColumn()]);

        $this->assertTrue($model->hasFlag(ModelFlags::FOO, ModelFlags::BAR));
        $this->assertFalse($model->hasFlag(ModelFlags::FOO, ModelFlags::BAR, ModelFlags::BAZ));
        $this->assertFalse($model->hasFlag(ModelFlags::QUX, ModelFlags::BAR, ModelFlags::BAZ));
        $this->assertTrue($model->hasAnyFlag(ModelFlags::FOO, ModelFlags::BAR, ModelFlags::BAZ));
        $this->assertFalse($model->hasAnyFlag(ModelFlags::QUX));

        $model->removeFlag(ModelFlags::FOO);

        $this->assertEquals(ModelFlags::BAR, $model->attributes[$model->flagsColumn()]);
    }
}