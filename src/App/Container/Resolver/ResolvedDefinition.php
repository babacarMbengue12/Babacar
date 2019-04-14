<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 22:16
 */

namespace Babacar\Container\Resolver;


class ResolvedDefinition
{
    private $resolved = [];


    public function has($name=null):bool
    {
        if (!is_null($name) && !empty($name) && !is_object($name)) {
            return isset($this->resolved[$name]);
        }

        return false;

    }//end has()


    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->resolved[$name];
        }

        return false;

    }//end get()


    public function set(string $name, $value)
    {
        $this->resolved[$name] = $value;

    }//end set()


}//end class
