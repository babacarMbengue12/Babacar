<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 13:34
 */

namespace Babacar\Container\Collector;



use Babacar\Container\Definition\FactoryDefinition;

class FactoryCollector
{




    private $factories = [];


    public function add(string $name, FactoryDefinition $definition)
    {
        if (is_null($definition->getName())) {
            $definition->setName($name);
        }

          $this->factories[$definition->getName()] = $definition;

    }//end add()


    public function has(string $name)
    {
        return isset($this->factories[$name]);

    }//end has()


    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->factories[$name];
        }

        return false;

    }//end get()


}//end class
