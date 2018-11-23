<?php

namespace Reinder83\BinaryFlags;

class Flag
{
    /**
     * @param int   $mask
     * @param mixed ...$flags
     *
     * @return bool
     */
    public static function has(int $mask, ...$flags)
    {
        if (!empty($flags) && is_array($flags[0])) {
            $flags = $flags[0];
        }

        $flags = array_sum(array_unique($flags));

        return ($mask & $flags) === $flags;
    }

    /**
     * @param int   $mask
     * @param mixed ...$flags
     *
     * @return bool
     */
    public static function hasAny(int $mask, ...$flags)
    {
        if (!empty($flags) && is_array($flags[0])) {
            $flags = $flags[0];
        }

        $flags = array_sum(array_unique($flags));

        return ($mask & $flags) > 0;
    }

    /**
     * @param int   $mask
     * @param mixed ...$flags
     *
     * @return int
     */
    public static function add(int $mask, ...$flags)
    {
        if (!empty($flags) && is_array($flags[0])) {
            $flags = $flags[0];
        }

        return $mask | array_sum(array_unique($flags));
    }

    /**
     * @param int   $mask
     * @param mixed ...$flags
     *
     * @return int
     */
    public static function remove(int $mask, ...$flags)
    {
        if (!empty($flags) && is_array($flags[0])) {
            $flags = $flags[0];
        }

        return $mask & ~array_sum(array_unique($flags));
    }
}