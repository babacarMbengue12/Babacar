<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/12/2018
 * Time: 09:07
 */

namespace Babacar\QueryBuilder;


use Babacar\Container\Container;

class Hydrator
{


    /**
     * @param  array         $values
     * @param  object|string $obj
     * @param  Container     $container
     * @return mixed|string
     * @throws \ReflectionException
     */
    public static function hydrate(array $values, $obj)
    {

       $obj = new $obj();

        foreach ($values as $k => $v) {
            $setter = self::getSetter($k);
            if (method_exists($obj, $setter)) {
                if(!is_null($v) && !empty($v) && !is_object($v))
                $obj->$setter($v);
            } else {
                $obj->$k = $v;
            }
        }


         return $obj;

    }//end hydrate()


    private static function getSetter($k)
    {
        return 'set'.join("", array_map('ucfirst', explode('_', $k)));

    }//end getSetter()


    public static function DisHydrate($user)
    {
        $reflexion  = new \ReflectionClass($user);
        $properties = $reflexion->getProperties();

        $returns = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $method = self::getGetter($property->getName());
            if (method_exists($user, $method)) {
                $value = $user->$method();
                if (!is_array($value) && !is_object($value) && !is_null($value)) {
                    $returns[$property->getName()] = $value;
                }
            }
        }

        return $returns;

    }//end DisHydrate()


    private static function getGetter($name)
    {
        return 'get'.join("", array_map('ucfirst', explode('_', $name)));

    }//end getGetter()


}//end class
