<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 13:45
 */

namespace Babacar\Container\Collector;



use Babacar\Container\Definition\ObjectDefinition;

class ObjectCollector
{
    private $objects = [];


    public function add(string $name, ObjectDefinition $definition)
    {

        $definition->setName($name);

        $this->objects[$definition->getName()] = $definition;

    }//end add()


    public function has(string $name)
    {
        return isset($this->objects[$name]);

    }//end has()


    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->objects[$name];
        }

        return false;

    }//end get()


}//end class
