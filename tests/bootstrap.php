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
        const QUX = Bits::BIT_4;
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
        const QUX = Bits::BIT_4;

        protected $binaryCustomFlagNames = [
            self::FOO => 'My foo description',
            self::BAR => 'My bar description',
            self::BAZ => 'My baz description',
            self::QUX => 'My qux description',
        ];
    }

    /**
     * Simple Example class that only defines constants
     *
     * @package Reinder83\BinaryFlags
     */
    class ModelFlags extends BinaryFlags
    {
        const FOO = Bits::BIT_1;
        const BAR = Bits::BIT_2;
        const BAZ = Bits::BIT_3;
        const QUX = Bits::BIT_4;

        public $attributes = [];

        /**
         * @param int|null $valueFromDB
         *
         * @return \Reinder83\BinaryFlags\Tests\ModelFlags
         */
        public static function newInstance(int $valueFromDB = null)
        {
            $binaryFlags = new static();

            if(!is_null($valueFromDB)) {
                $binaryFlags->attributes[$binaryFlags->flagsColumn()] = $valueFromDB;
            }

            $binaryFlags->initializeHasBinaryFlags();

            return $binaryFlags;
        }

        public function getAttribute($key)
        {
            return $this->attributes[$key] ?? null;
        }

        public function setAttribute($key, $value)
        {
            $this->attributes[$key] = $value;
        }
    }
}
