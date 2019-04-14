<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 14:40
 */
declare(strict_types=1);

namespace Babacar\Router;

class Router
{

    private static $_instance;

    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    /**
     * @param string      $path
     * @param string      $action
     * @param null|string $name
     * @return Router
     * @throws Exceptions\DuplicateRouteNameException
     */
    public static function get(string $path, $action, ?string $name = null): self
    {
        return self::set($path, $action, $name, "GET");
    }

    /**
     * @param string      $path
     * @param string      $action
     * @param null|string $name
     * @return Router
     * @throws Exceptions\DuplicateRouteNameException
     */
    public static function post(string $path, $action, ?string $name = null): self
    {
        return self::set($path, $action, $name, "POST");
    }

    /**
     * @param string      $path
     * @param string      $action
     * @param null|string $name
     * @return Router
     * @throws Exceptions\DuplicateRouteNameException
     */
    public static function put(string $path, $action, ?string $name = null): self
    {
        return self::set($path, $action, $name, "PUT");
    }

    /**
     * @param string      $path
     * @param string      $action
     * @param null|string $name
     * @return Router
     * @throws Exceptions\DuplicateRouteNameException
     */
    public static function delete(string $path, $action, ?string $name = null): self
    {
        return self::set($path, $action, $name, "DELETE");
    }

    /**
     * @param string      $path
     * @param string      $action
     * @param null|string $name
     * @return Router
     * @throws Exceptions\DuplicateRouteNameException
     */
    public static function patch(string $path, $action, ?string $name = null): self
    {
        return self::set($path, $action, $name, "PATCH");
    }

    public static function match($url): ?RouteResult
    {
        return RouteMatcher::match($url);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param array  $query_args
     * @return string|null
     * @throws Exceptions\RouteNotFoundException
     * @throws Exceptions\RouteParameterNameException
     * @throws Exceptions\RouteParameterNotMatchException
     * @throws Exceptions\RouteParametersMissingException
     */
    public static function generateUri(string $name, array $parameters = [], array $query_args = []): ?string
    {
        return RouteGenerator::generate($name, $parameters, $query_args);
    }

    /**
     * @param string      $path
     * @param string      $action
     * @param null|string $name
     * @param             $method
     * @return Router
     * @throws Exceptions\DuplicateRouteNameException
     */
    private static function set(string $path, $action, ?string $name = null, $method)
    {
        RouteCollector::addRoute(new Route($path, $action, $method, $name));

        return self::getInstance();
    }


}