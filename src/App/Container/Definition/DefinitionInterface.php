<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 12:00
 */

namespace Babacar\Container\Definition;


interface DefinitionInterface
{


    /**
     * @param  mixed ...$args
     * @return self
     */
    public function constructor(...$args);


    /**
     * @param  string        $parameterName
     * @param  $parameterArgs
     * @return self
     */
    public function constructorParameter(string $parameterName, $parameterArgs);


    public function isSingleTon():bool;


    public function getName():?string;


    public function setName(string $name);


}//end interface
