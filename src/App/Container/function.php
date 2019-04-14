<?php
namespace Babacar\Container;




use Babacar\Container\Definition\ArrayDefinition;
use Babacar\Container\Definition\ObjectDefinition;
use Babacar\Container\Definition\FactoryDefinition;

/*
 * @param  $class
 * @return ObjectDefinition
 */
if (!function_exists('Babacar\Container\get')) {


    function get($class):ObjectDefinition
    {
        return new ObjectDefinition($class);

    }//end get()


}

if (!function_exists('Babacar\Container\make')) {


    function make($className):ObjectDefinition
    {
        return new ObjectDefinition($className, false);

    }//end make()


}

if (!function_exists('Babacar\Container\delete')) {


    function delete($className): ArrayDefinition
    {
        return new ArrayDefinition($className, false, true);

    }//end delete()


}

if (!function_exists('Babacar\Container\add')) {


    function add($arrays):ArrayDefinition
    {
        return new ArrayDefinition($arrays);

    }//end add()


}

if (!function_exists('Babacar\Container\object')) {


    function object($className,$isSingleton = false):ObjectDefinition
    {
        return new ObjectDefinition($className,$isSingleton);

    }//end object()


}

/*
 * @param  $factory
 * @return FactoryDefinition
 */
if (!function_exists('Babacar\Container\factory')) {


    function factory($factory,$isSingleton = false):FactoryDefinition
    {
        return new FactoryDefinition($factory,$isSingleton);

    }//end factory()


}
