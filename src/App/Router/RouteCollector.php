<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 14:42
 */

namespace Babacar\Router;


use Babacar\Router\Exceptions\DuplicateRouteNameException;

class RouteCollector
{

    /**
     * @var Route[][] $routes
     */
    public static $routes = [
        "GET" => [],
        "POST" => [],
        "DELETE" => [],
        "PUT" => [],
        "PATCH" => []
    ];

    /**
     * @param Route $route
     * @throws DuplicateRouteNameException
     */
    public static function addRoute(Route $route)
    {
        self::verifyRoute($route);
        if(!is_null($route->getName()))
        {
            self::$routes[$route->getMethod()][$route->getName()] = $route;
        }
        else{
            self::$routes[$route->getMethod()][] = $route;
        }
    }

    /**
     * @param Route $route
     * @throws DuplicateRouteNameException
     */
    private static function verifyRoute(Route $route)
    {
        $routes = self::$routes[$route->getMethod()];
        if($route->getName() !== null  && !empty($routes)){
           foreach ($routes as $k => $v)
           {
               if($k === $route->getName())
                   throw new DuplicateRouteNameException($route->getName());
           }

        }
    }

    public static function get($method)
    {
        return self::$routes[$method];
    }

    public static function getByName(string $name)
    {
        foreach (self::$routes as $route){
            foreach ($route as $item){
                if($item->getName() === $name){
                    return $item;
                }
            }
        }
        return null;
    }

}