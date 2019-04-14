<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 11:58
 */

namespace Babacar\Container\Definition;


class FactoryDefinition implements DefinitionInterface
{
    /**
     * @var string|callable
     */
    private $factory;
    /**
     * @var array
     */
    private $constructor = [];

    /**
     * @var array
     */
    private $constructorParameter = [];
    /**
     * @var string
     */
    private $name;
    /**
     * @var boolean
     */
    private $singleTon;


    public function __construct($factory, bool $singleTon=true)
    {
         $this->factory   = $factory;
         $this->singleTon = $singleTon;

    }//end __construct()


    /**
     * @param  mixed ...$args
     * @return self
     */
    public function constructor(...$args): self
    {
        $this->constructor = $args;
        return $this;

    }//end constructor()


    /**
     * @param  string        $parameterName
     * @param  $parameterArgs
     * @return self
     */
    public function constructorParameter(string $parameterName, $parameterArgs): self
    {
        $this->constructorParameter[$parameterName] = $parameterArgs;
        return $this;

    }//end constructorParameter()


    /**
     * @return callable|string
     */
    public function getFactory()
    {
        return $this->factory;

    }//end getFactory()


    /**
     * @return array
     */
    public function getConstructor(): array
    {
        return $this->constructor;

    }//end getConstructor()


    /**
     * @return array
     */
    public function getConstructorParameter(): array
    {

        return $this->constructorParameter;

    }//end getConstructorParameter()


    public function setName(string $name)
    {
        $this->name = $name;

    }//end setName()


    public function getName():?string
    {
        return $this->name;

    }//end getName()


    public function singleTon(bool $flag=true)
    {
        $this->singleTon = $flag;
        return $this;

    }//end singleTon()


    public function isSingleTon(): bool
    {
        return $this->singleTon;

    }//end isSingleTon()


}//end class
