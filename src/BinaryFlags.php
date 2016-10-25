<?php namespace Reinder83\BinaryFlags;

use ReflectionClass;

/**
 * This class holds useful methods for checking, adding or removing binary flags
 *
 * @author Reinder
 *
 * Possible values for bitwise flags:
 *
 * 1
 * 2
 * 4
 * 8
 * 16 (5)
 * 32
 * 64
 * 128
 * 256
 * 512 (10)
 * 1024
 * 2048
 * 4096
 * 8192
 * 16384 (15)
 * 32768
 * 65536
 * 131072
 * 262144
 * 524288 (20)
 * 1048576
 * 2097152
 * 4194304
 * 8388608
 * 16777216 (25)
 * 33554432
 * 67108864
 * 134217728
 * 268435456
 * 536870912 (30)
 * 1073741824
 * 2147483648
 * 4294967296
 * 8589934592
 * 17179869184 (35)
 * 34359738368
 * 68719476736
 * 137438953472
 * 274877906944
 * 549755813888 (40)
 * 1099511627776
 * 2199023255552
 * 4398046511104
 * 8796093022208
 * 17592186044416 (45)
 * 35184372088832
 * 70368744177664
 * 140737488355328
 * 281474976710656
 * 562949953421312 (50)
 * 1125899906842624
 * 2251799813685248
 * 4503599627370496
 * 9007199254740992
 * 18014398509481984 (55)
 * 36028797018963968
 * 72057594037927936
 * 144115188075855872
 * 288230376151711744
 * 576460752303423488 (60)
 * 1152921504606846976
 * 2305843009213693952
 * 4611686018427387904
 * 9223372036854775808 (64)
 */
abstract class BinaryFlags
{
    /**
     * This will hold the mask for checking against
     * @var int
     */
    protected $mask;

    /**
     * This will be called on changes
     * @var callable
     */
    protected $onModifyCallback;

    /**
     * Initiate class
     * @param int [$mask = 0]
     * @param callable [$onModify = null]
     */
    public function __construct($mask = 0, callable $onModify = null)
    {
        $this->setMask($mask);

        // set onModify callback if specified
        if ($onModify !== null) {
            $this->setOnModifyCallback($onModify);
        }
    }

    /**
     * Check mask against constants
     * and return the names or descriptions in a comma separated string or as array
     *
     * @param int [$mask = null]
     * @param bool [$asArray = false]
     * @return string|array
     */
    public function getFlagNames($mask = null, $asArray = false)
    {
        $mask = $mask ?: $this->mask;

        $calledClass = get_called_class();

        $reflection = new ReflectionClass($calledClass);
        $constants = $reflection->getConstants();

        $names = array();
        if ($constants) {
            foreach ($constants as $constant => $flag) {
                if ($mask & $flag) {
                    $names[] = method_exists($calledClass, 'getAllFlags') && !empty($calledClass::getAllFlags()[$flag])
                        ? $calledClass::getAllFlags()[$flag]
                        : implode('', array_map('ucfirst', explode('_', strtolower($constant))));
                }
            }
        }
        return $asArray ? $names : implode(', ', $names);
    }

    /**
     * Set an function which will be called upon changes
     *
     * @param callable $onModify
     */
    public function setOnModifyCallback(callable $onModify)
    {
        $this->onModifyCallback = $onModify;
    }

    /**
     * Will be called upon changes and execute the callback, if set
     */
    protected function onModify()
    {
        if (is_callable($this->onModifyCallback)) {
            call_user_func($this->onModifyCallback, $this);
        }
    }

    /**
     * This method will set the mask where will be checked against
     *
     * @param int $mask
     * @return BinaryFlags
     */
    public function setMask($mask)
    {
        $before = $this->mask;
        $this->mask = $mask;

        if ($before !== $this->mask) {
            $this->onModify();
        }

        return $this;
    }

    /**
     * This method will return the current mask
     *
     * @return int
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * This will set flag(s) in the current mask
     *
     * @param int $flag
     * @return BinaryFlags
     */
    public function addFlag($flag)
    {
        $before = $this->mask;
        $this->mask |= $flag;

        if ($before !== $this->mask) {
            $this->onModify();
        }
        return $this;
    }

    /**
     * This will remove a flag(s) (if it's set) in the current mask
     *
     * @param int $flag
     * @return BinaryFlags
     */
    public function removeFlag($flag)
    {
        $before = $this->mask;
        $this->mask &= ~$flag;

        if ($before !== $this->mask) {
            $this->onModify();
        }
        return $this;
    }

    /**
     * Check if given flag(s) are set in the current mask
     * By default it will check all bits in the given flag
     * When you want to match any of the given flags set $check_all to false
     *
     * @param int $flag
     * @param bool $checkAll
     * @return bool
     */
    public function checkFlag($flag, $checkAll = true)
    {
        $result = $this->mask & $flag;
        return $checkAll ? $result == $flag : $result > 0;
    }
}
