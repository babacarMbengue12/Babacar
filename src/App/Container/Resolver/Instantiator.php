<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 23:16
 */

namespace Babacar\Container\Resolver;


use Babacar\Container\Container;
use Babacar\Exception\NotFoundException;
class Instantiator
{


    public static function getInstance($name, array $parameters=[], Container $container, $is_)
    {
        if ($is_) {
            if ($container->memoryHave($name)) {
                return $container->getOnMemory($name);
            }
        }

        $reflexion = new \ReflectionClass($name);

        if ($reflexion->isInstantiable()) {
            $constructor = $reflexion->getConstructor();
            if (is_null($constructor)) {
                return $reflexion->newInstanceWithoutConstructor();
            }

            $numberParameters = $constructor->getNumberOfParameters();
            if (count($parameters) !== $numberParameters) {
                foreach ($constructor->getParameters() as $parameter) {
                    $pramName = $parameter->getName();
                    if ($parameter->getClass()) {
                        $class = $parameter->getClass()->getName();
                        if ($container->memoryHave($class)) {
                            $pram = $container->getOnMemory($class);
                        } else if ($container->has($class)) {
                            $pram = $container->get($class);
                        } else {
                            $pram = $container->getHelper()->get($class);
                        }

                        if (!in_array($pram, $parameters)) {
                            $parameters[$pramName] = $pram;
                        }
                    }
                }
            }//end if

            return $reflexion->newInstanceArgs($parameters);
        }//end if

        throw new NotFoundException($name);

    }//end getInstance()


}//end class
