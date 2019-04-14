<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 12:29
 */

namespace Babacar\Container\Definition;


class ObjectDefinition implements DefinitionInterface
{

    /**
     * @var string
     */
    private $value;

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


    public function __construct($value, bool $singleTon=true)
    {

        $this->value = $value;

        if (is_object($value)) {
            $this->setName(get_class($value));
        }

        $this->singleTon = $singleTon;

    }//end __construct()


    /**
     * @param  mixed ...$args
     * @return self
     */
    public function constructor(...$args)
    {
        $this->constructor = $args;

        return $this;

    }//end constructor()


    public function singleTon(bool $flag=true)
    {
        $this->singleTon = $flag;
        return $this;

    }//end singleTon()


    /**
     * @param  string        $parameterName
     * @param  $parameterArgs
     * @return self
     */
    public function constructorParameter(string $parameterName, $parameterArgs)
    {
        $this->constructorParameter[$parameterName] = $parameterArgs;

        return $this;

    }//end constructorParameter()


    /**
     * @return mixed
     */
    public function getValue()
    {

        return $this->value;

    }//end getValue()


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


    public function getName():?string
    {
        return $this->name;

    }//end getName()


    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;

    }//end setName()


    public function isSingleTon(): bool
    {
        return $this->singleTon;

    }//end isSingleTon()


}//end class
