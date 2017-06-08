<?php

/**
 * Create a test classes which extends from our abstract class
 */

namespace Reinder83\BinaryFlags\Tests {

    require __DIR__ . '/../vendor/autoload.php';

    use Reinder83\BinaryFlags\BinaryFlags;
    use Reinder83\BinaryFlags\Bits;

    /**
     * Simple Example class that only defines constants
     *
     * @package Reinder83\BinaryFlags
     */
    class ExampleFlags extends BinaryFlags
    {
        const FOO = Bits::BIT_1;
        const BAR = Bits::BIT_2;
        const BAZ = Bits::BIT_3;
    }

    /**
     * Simple example class that defines constants and their descriptions
     *
     * @package Reinder83\BinaryFlags
     */
    class ExampleFlagsWithNames extends BinaryFlags
    {
        const FOO = Bits::BIT_1;
        const BAR = Bits::BIT_2;
        const BAZ = Bits::BIT_3;

        public static function getAllFlags()
        {
            return [
                static::FOO => 'My foo description',
                static::BAR => 'My bar description',
                static::BAZ => 'My baz description',
            ];
        }

    }
}
