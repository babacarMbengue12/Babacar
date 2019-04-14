<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 13:40
 */

namespace Babacar\Container\Collector;


class InstancesCollector
{
    /**
     * @var object[]
     */
    public $instances = [];


    public function add($obj, $name=null)
    {
        if (is_null($name)) {
            $this->instances[get_class($obj)] = $obj;
        } else {
            $this->instances[$obj] = $name;
        }

    }//end add()


    public function has($name)
    {
        if (!is_null($name) && !empty($name) && !is_object($name)) {
            return isset($this->instances[$name]);
        }

        return false;

    }//end has()


    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->instances[$name];
        }

        return false;

    }//end get()


}//end class
