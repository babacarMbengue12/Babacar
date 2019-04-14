<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 14:03
 */

namespace Babacar\Container\Definition;


use Babacar\Container\Container;
use Babacar\Exception\NotFoundException;
use ReflectionClass;

class Autowaire
{
    /**
     * @var Container
     */
    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;

    }//end __construct()


    public function get(string $className, array $prams=[], $is_=true)
    {

        if ($is_) {
            if ($this->container->memoryHave($className)) {
                return $this->container->getOnMemory($className);
            }
        }

        if (!class_exists($className)) {
            var_dump($className);
            throw new NotFoundException('la cle '.$className.' n\'exist pas');
        }

        $reflexion = new ReflectionClass($className);
        if ($reflexion->isInstantiable()) {
            $constructor = $reflexion->getConstructor();
            $keys        = [];
            if (!is_null($constructor)) {
                $parameters = $constructor->getParameters();
                if (!empty($parameters)) {
                    foreach ($parameters as $parameter) {
                        $keys[] = $parameter->getName();
                        if ($parameter->getClass()) {
                            $class = $parameter->getClass()->getName();
                            if ($this->container->memoryHave($class)) {
                                $pram = $this->container->getOnMemory($class);

                                if (!isset($prams[$parameter->getName()])) {
                                    $prams[$parameter->getName()] = $pram;
                                }
                            } else if ($this->container->has($class)) {
                                $pram = $this->container->get($class);
                                if (!isset($prams[$parameter->getName()])) {
                                    $prams[$parameter->getName()] = $pram;
                                }
                            } else {
                                $pram = $this->get($class, []);
                                if (!isset($prams[$parameter->getName()])) {
                                    $prams[$parameter->getName()] = $pram;
                                }
                            }
                        } else if ($parameter->isDefaultValueAvailable()) {
                            $prams[$parameter->getName()] = $parameter->getDefaultValue();
                        }//end if
                    }//end foreach
                }//end if

                $prams      = array_map(
                    function ($k) use ($prams) {
                        if (isset($prams[$k])) {
                            return $prams[$k];
                        }

                        return null;
                    },
                    $keys
                );
                $controller = $reflexion->newInstanceArgs($prams);
            } else {
                $controller = $reflexion->newInstance();
            }//end if
            // if($is_)
            // $this->container->set($className,$controller);
            return $controller;
        } else {
            return null;
            throw new NotFoundException($className);
        }//end if

    }//end get()


    public function getMethod($obj, $methodName, array $prams=[])
    {

        $reflexion = new ReflectionClass($obj);
        if ($reflexion->hasMethod($methodName)) {
            $method = $reflexion->getMethod($methodName);

            $parameters = $method->getParameters();

            if (empty($parameters)) {
                return [
                    $obj,
                    $methodName,
                    $prams,
                ];
            } else {
                $keys = [];

                foreach ($parameters as $parameter) {
                    $keys[] = $parameter->getName();
                    if ($parameter->getClass()) {
                        $class = $parameter->getClass()->getName();
                        if ($this->container->memoryHave($class)) {
                            $pram = $this->container->getOnMemory($class);
                            if (!isset($prams[$parameter->getName()])) {
                                $prams[$parameter->getName()] = $pram;
                            }
                        } else if ($this->container->has($class)) {
                            $pram = $this->container->get($class);
                            if (!isset($prams[$parameter->getName()])) {
                                $prams[$parameter->getName()] = $pram;
                            }
                        } else {
                            $pram = $this->get($class);
                            if (!isset($prams[$parameter->getName()])) {
                                $prams[$parameter->getName()] = $pram;
                            }
                        }

                        // var_dump("<h1>".$method->getName()." => $class</h1>",$pram);
                    } else if ($parameter->isDefaultValueAvailable()) {
                        $prams[$parameter->getName()] = $parameter->getDefaultValue();
                    }//end if
                }//end foreach

                $prams = array_map(
                    function ($k) use ($prams) {
                        if (isset($prams[$k])) {
                            return $prams[$k];
                        }

                        return null;
                    },
                    $keys
                );

                return [
                    $obj,
                    $methodName,
                    $prams,
                ];
            }//end if
        } else {
            throw new NotFoundException($methodName);
        }//end if

    }//end getMethod()


}//end class
