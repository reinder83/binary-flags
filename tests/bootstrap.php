<?php

/**
 * Create a test class which extends from our abstract class
 */

namespace Reinder83\BinaryFlags\Tests {

    use Reinder83\BinaryFlags\BinaryFlags;

    /**
     * Simple Example class that only defines constants
     *
     * @package Reinder83\BinaryFlags
     */
    class ExampleFlags extends BinaryFlags {

        const FOO = 1;
        const BAR = 2;
        const BAZ = 4;

    }

    /**
     * Simple example class that defines constants and their descriptions
     *
     * @package Reinder83\BinaryFlags
     */
    class ExampleFlagsWithNames extends BinaryFlags  {

        const FOO = 1;
        const BAR = 2;
        const BAZ = 4;

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