<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 16:18
 */
namespace Babacar\Container\Resolver;


use Babacar\Container\Container;

use Babacar\Container\Definition\ObjectDefinition;
use Babacar\Container\Definition\FactoryDefinition;

class FactoryResolver
{
    /**
     * @var Container
     */
    private $container;


    public function __construct(Container $container)
    {
          $this->container = $container;

    }//end __construct()


    public function resolve(FactoryDefinition $definition, $is_=true)
    {
        if (!$is_) {
            $is_ = false;
        } else {
            $is_ = $definition->isSingleTon();
        }

        if ($is_) {
            if ($this->container->memoryHave($definition)) {
                return $this->container->getOnMemory($definition);
            }
        }

        $factory = $definition->getFactory();

        $parameters = array_merge($definition->getConstructor(), $definition->getConstructorParameter());

        $prams = [];

        foreach ($parameters as $k => $parameter) {
            if (!is_object($parameter) && !class_exists($parameter)) {
                $prams[$k] = $parameter;
            } else {
                $prams[$k] = $this->getPrams($parameter);
            }
        }

        if (is_callable($factory)) {
            if (empty($prams)) {
                $prams[] = $this->container;
            }

            return call_user_func_array($factory, $prams);
        } else {
            $obj = $this->container->getHelper()->get($factory, $prams, $is_);

            $method = $this->container->getHelper()->getMethod($obj, '__invoke', $prams);

            return call_user_func_array([$obj, '__invoke'], $method[2]);
        }

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
        } else if ($this->container->has($parameter)) {
            return $this->container->get($parameter);
        } else if (class_exists($parameter)) {
            return $this->container->getHelper()->get($parameter, []);
        } else {
            return $parameter;
        }

    }//end getPrams()


}//end class
