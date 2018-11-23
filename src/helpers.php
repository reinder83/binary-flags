<?php

if (!function_exists('class_constants')) {

    /**
     * @param $class
     * @return array
     * @throws ReflectionException
     */
    function class_constants($class)
    {
        return (new ReflectionClass($class))->getConstants();
    }
}