<?php
namespace Babacar\Container;

use Babacar\Container\Collector\{
    StringCollector, ArrayDefinitionCollector, FactoryCollector, InstancesCollector, ObjectCollector
};
use Babacar\Container\Definition\{
    ArrayDefinition, Autowaire, ObjectDefinition, FactoryDefinition
};
use Babacar\Container\Resolver\{
    DefinitionResolver, FactoryResolver, ResolvedDefinition
};
use Babacar\Exception\{
    DefinitionNotFoundException
};
use Psr\Container\{
    ContainerInterface
};

class Container implements ContainerInterface
{


    /**
     * @var FactoryCollector
     */
    private $factoryCollector;
    /**
     * @var ObjectCollector
     */
    private $objectCollector;

    /**
     * @var Autowaire
     */
    private $helper;
    /**
     * @var FactoryResolver
     */
    private $factoryResolver;
    /**
     * @var DefinitionResolver
     */
    private $definitionResolver;
    /**
     * @var ResolvedDefinition
     */
    private $resolvedDefinition;
    /**
     * @var boolean
     */
    private $init = false;
    /**
     * @var StringCollector
     */
    private $stringCollector;
    /**
     * @var InstancesCollector
     */
    private $instancesCollector;
    /**
     * @var ArrayDefinitionCollector
     */
    private $arrayDefinitionCollector;
    /**
     * @var array
     */
    private $temporaryObjects = [];


    /**
     * Container constructor.
     */
    public function __construct()
    {
        if ($this->init === false) {
            $this->init();
        }

    }//end __construct()


    public function addDefinition($definitions)
    {
        if ($this->init === false) {
            $this->init();
        }

        if (!is_array($definitions) && !is_string($definitions)) {
            throw new DefinitionNotFoundException($definitions);
        }

        if (!is_array($definitions)) {
            if (file_exists($definitions)) {
                $definitions = include $definitions;
            } else {
                throw new DefinitionNotFoundException($definitions);
            }
        }

        foreach ($definitions as $key => $definition) {
            if ($definition instanceof ObjectDefinition) {
                $this->objectCollector->add($key, $definition);
            } else if ($definition instanceof ArrayDefinition) {
                $this->arrayDefinitionCollector->add($key, $definition);
            } else if (is_array($definition)) {
                $this->arrayDefinitionCollector->add($key, new ArrayDefinition($definition));
            } else if ($definition instanceof FactoryDefinition) {
                $this->factoryCollector->add($key, $definition);
            } else {
                $this->stringCollector->add($key, $definition);
            }
        }

        return true;

    }//end addDefinition()


    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws \Psr\Container\ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     * @throws \ReflectionException
     */
    public function get($name, $is_=true)
    {
        if ($is_) {
            if ($this->memoryHave($name)) {
                return $this->getOnMemory($name);
            }
        }

        if ($this->factoryCollector->has($name)) {
            /*
             * @var $factory FactoryDefinition
             */
            $factory = $this->factoryCollector->get($name);
            $obj     = $this->factoryResolver->resolve($factory, $is_);
            if ($obj && ($is_ && $factory->isSingleTon())) {
                $this->set($name, $obj);
            }

            return $obj;
        } else if ($this->objectCollector->has($name)) {
            /*
             * @var $definition ObjectDefinition
             */
            $definition = $this->objectCollector->get($name);

             $obj = $this->definitionResolver->resolve($definition, $is_);
            if ($obj && ($is_ && $definition->isSingleTon())) {
                $this->set($name, $obj);
            }

             return $obj;
        }//end if

        $obj = $this->helper->get($name, [], $is_);

        return $obj;

    }//end get()


    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($name)
    {
        if (is_array($name) || is_null($name) || is_object($name)) {
            return false;
        }

        if ($this->factoryCollector->has($name)) {
            return true;
        }

        if ($this->objectCollector->has($name)) {
            return true;
        }

        return false;

    }//end has()


    public function getOnMemory(string $name)
    {
        if ($this->temporaryHas($name)) {
            return $this->temporaryGet($name);
        }

        if ($this->resolvedDefinition->has($name)) {
            return $this->resolvedDefinition->get($name);
        }

        if ($this->instancesCollector->has($name)) {
            return $this->instancesCollector->get($name);
        }

        if ($this->stringCollector->has($name)) {
            return $this->stringCollector->get($name);
        }

        if ($this->arrayDefinitionCollector->has($name)) {
            return $this->arrayDefinitionCollector->get($name);
        }

        if ($this->has($name)) {
            $obj = $this->get($name);
            if ($obj) {
                $this->resolvedDefinition->set($name, $obj);
            }

            return $obj;
        }

        return false;

    }//end getOnMemory()


    public function memoryHave($name)
    {
        if (is_array($name) || is_null($name) || is_object($name)) {
            return false;
        }

        if ($this->temporaryHas($name)) {
            return true;
        }

        if ($this->resolvedDefinition->has($name)) {
            return true;
        } else if ($this->arrayDefinitionCollector->has($name)) {
            return true;
        } else if ($this->instancesCollector->has($name)) {
            return true;
        } else if ($this->stringCollector->has($name)) {
            return true;
        }

        return false;

    }//end memoryHave()


    /**
     * @param $id
     */
    public function set(string $name, $value)
    {
        if (!$this->has($name) || !$this->memoryHave($value)) {
            if (is_object($value)) {
                $this->instancesCollector->add($name, $value);
            } else if (is_array($value)) {
                $this->arrayDefinitionCollector->add($name, new ArrayDefinition($value));
            } else {
                $this->stringCollector->add($name, $value);
            }
        }

    }//end set()


    public function make($name)
    {
        return $this->get($name, false);

    }//end make()


    public function setInstance($instance)
    {
        $this->instancesCollector->add($instance);

    }//end setInstance()


    /**
     * @return Autowaire
     */
    public function getHelper(): Autowaire
    {
        return $this->helper;

    }//end getHelper()


    /**
     * @return FactoryResolver
     */
    public function getFactoryResolver(): FactoryResolver
    {
        return $this->factoryResolver;

    }//end getFactoryResolver()


    /**
     * @return DefinitionResolver
     */
    public function getDefinitionResolver(): DefinitionResolver
    {
        return $this->definitionResolver;

    }//end getDefinitionResolver()


    /**
     * @return ResolvedDefinition
     */
    public function getResolvedDefinition(): ResolvedDefinition
    {
        return $this->resolvedDefinition;

    }//end getResolvedDefinition()


    /**
     * @return StringCollector
     */
    public function getStringCollector(): StringCollector
    {
        return $this->stringCollector;

    }//end getStringCollector()


    private function init()
    {
        $this->init   = true;
        $this->helper = new Autowaire($this);

        $this->resolvedDefinition = new ResolvedDefinition();

        $this->instancesCollector = new InstancesCollector();

        $this->factoryCollector = new FactoryCollector();

        $this->arrayDefinitionCollector = new ArrayDefinitionCollector($this);

        $this->objectCollector = new ObjectCollector();

        $this->stringCollector = new StringCollector();

        $this->factoryResolver = new FactoryResolver($this);

        $this->definitionResolver = new DefinitionResolver($this);

        $this->instancesCollector->add($this);

    }//end init()


    /**
     * @return ArrayDefinitionCollector
     */
    public function getArrayDefinitionCollector(): ArrayDefinitionCollector
    {
        return $this->arrayDefinitionCollector;

    }//end getArrayDefinitionCollector()


    public function temporarySet($name, $obj)
    {
        $this->temporaryObjects[$name] = $obj;

    }//end temporarySet()


    public function temporaryHas($name)
    {
        return isset($this->temporaryObjects[$name]);

    }//end temporaryHas()


    public function temporaryGet($name)
    {
        if ($this->temporaryHas($name)) {
            $obj = $this->temporaryObjects[$name];
            unset($this->temporaryObjects[$name]);
            return $obj;
        }

        return null;

    }//end temporaryGet()


}//end class
