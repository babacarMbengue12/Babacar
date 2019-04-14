<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 13:40
 */

namespace Babacar\Container\Collector;


use Babacar\{
    Container\Container
};
use Babacar\Container\Definition\ArrayDefinition;
use Babacar\Container\Definition\FactoryDefinition;
use Babacar\Container\Definition\ObjectDefinition;

class ArrayDefinitionCollector
{
    /**
     * @var $definitions ArrayDefinition[]
     */
    private $definitions = [];
    /**
     * @var Container
     */
    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;

    }//end __construct()


    public function add($key, ArrayDefinition $definition)
    {
        $definition->setName($key);
        if (isset($this->definitions[$key])) {
            if ($definition->isRemovable()) {
                $this->definitions[$key] = $this->diff($key, $definition);
            } else {
                $this->definitions[$key]->add($definition->get());
            }
        } else {
            $this->definitions[$key] = $definition;
        }

    }//end add()


    public function has($name)
    {
        if (!is_null($name) && !empty($name) && !is_object($name)) {
            return isset($this->definitions[$name]);
        }

        return false;

    }//end has()


    public function get(string $name)
    {
        if ($this->has($name)) {
            $definitions = $this->definitions[$name]->get();
            return $this->resolve($definitions);
        }

        return false;

    }//end get()


    private function diff($key, ArrayDefinition $definition):ArrayDefinition
    {
        $array = array_diff($this->definitions[$key]->get(), $definition->get());
        $r     = [];
        foreach ($array as $k => $item) {
            if (is_string($k)) {
                $r[$k] = $item;
            } else {
                $r[] = $item;
            }
        }

        return (new ArrayDefinition($r))->setName($key);

    }//end diff()


    private function resolve(array $definitions)
    {
        $returns = [];
        foreach ($definitions as $k => $definition) {
            if ($definition instanceof ObjectDefinition) {
                $returns[$k] = $this->container->getDefinitionResolver()->resolve($definition);
            } else if ($definition instanceof FactoryDefinition) {
                $returns[$k] = $this->container->getFactoryResolver()->resolve($definition);
            } else if ($this->container->memoryHave($definition)) {
                $returns[$k] = $this->container->getOnMemory($definition);
            } else if (!is_array($definition) && class_exists($definition)) {
                $returns[$k] = $this->container->getHelper()->get($definition);
            } else {
                $returns[$k] = $definition;
            }
        }

        return $returns;

    }//end resolve()


}//end class
