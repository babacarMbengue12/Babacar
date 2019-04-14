<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 11:59
 */

namespace Babacar\Container\Collector;


use Babacar\Container\Definition\DefinitionInterface;
use Babacar\Exception\DefinitionNotFoundException;

class DefinitionCollector
{

    /**
     * @var DefinitionInterface[]
     */
    private $definitions = [];


    /**
     * @param string              $name
     * @param DefinitionInterface $definition
     */
    public function addDefinition(string $name, DefinitionInterface $definition)
    {
        if ($definition->getName() === null) {
            $definition->setName($name);
        }

        $this->definitions[$definition->getName()] = $definition;

    }//end addDefinition()


    /**
     * @param  string $name
     * @return DefinitionInterface
     * @throws DefinitionNotFoundException
     */
    public function getDefinition(string $name):DefinitionInterface
    {
        if ($this->hasDefinition($name)) {
            return $this->definitions[$name];
        }

         throw new DefinitionNotFoundException('Definition not Found: '.$name);

    }//end getDefinition()


    /**
     * @param  string $name
     * @return bool
     */
    public function hasDefinition(string $name):bool
    {
        return isset($this->definitions[$name]);

    }//end hasDefinition()


}//end class
