<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 13:40
 */

namespace Babacar\Container\Collector;


class StringCollector
{
    /**
     * @var $definition
     */
    private $definition = [];


    public function add($key, $definitions)
    {

            $this->definition[$key] = $definitions;

    }//end add()


    public function has($name)
    {
        if (!is_null($name) && !empty($name) && !is_object($name)) {
            return isset($this->definition[$name]);
        }

        return false;

    }//end has()


    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->definition[$name];
        }

        return false;

    }//end get()


}//end class
