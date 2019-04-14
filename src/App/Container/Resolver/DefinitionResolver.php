<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 16:18
 */
namespace Babacar\Container\Resolver;


use Babacar\Container\Container;
use Babacar\Container\Definition\ArrayDefinition;
use Babacar\Container\Definition\ObjectDefinition;
use Babacar\Container\Definition\FactoryDefinition;

class DefinitionResolver
{
    /**
     * @var Container
     */
    private $container;


    public function __construct(Container $container)
    {
          $this->container = $container;

    }//end __construct()


    public function resolve(ObjectDefinition $definition, $is_=true)
    {

        if (!$is_ || !$definition->isSingleTon()) {
            $is_ = false;
        }

        $name = $definition->getValue();

        if ($is_) {
            if ($this->container->memoryHave($name)) {
                return $this->container->getOnMemory($name);
            }
        }

        if (is_object($name) && $is_) {
            $this->container->set(get_class($name), $name);
            return $name;
        }

        $parameters = array_merge($definition->getConstructor(), $definition->getConstructorParameter());
          $prams    = [];

        foreach ($parameters as $k => $constructor) {
            if (!is_object($constructor) && !class_exists($constructor)) {
                $prams[$k] = $constructor;
            } else {
                $prams[$k] = $this->getPrams($constructor);
            }
        }

            $obj = Instantiator::getInstance($name, $prams, $this->container, $is_);

        if ($obj && $is_) {
            $this->container->set($definition->getValue(), $obj);
        }

            return $obj;

    }//end resolve()


    private function getPrams($parameter)
    {

        if ($this->container->memoryHave($parameter)) {
            return $this->container->getOnMemory($parameter);
        }

        if ($parameter instanceof FactoryDefinition) {
            return $this->container->get($parameter->getFactory());
        }

        if ($parameter instanceof ObjectDefinition) {
            return $this->container->get($parameter->getValue());
        }

        if ($this->container->has($parameter)) {
            return $this->container->get($parameter);
        } else {
            return $this->container->getHelper()->get($parameter, []);
        }

    }//end getPrams()


}//end class
