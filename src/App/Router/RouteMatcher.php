<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 15:07
 */

namespace Babacar\Router;


use Psr\Http\Message\ServerRequestInterface;

class RouteMatcher
{
    public static function match($url): ?RouteResult
    {
        if ($url instanceof ServerRequestInterface) {
            $method = $url->getMethod();
            $url = $url->getUri()->getPath();
        } else {
            $method = $_SERVER['REQUEST_METHOD'] ?? "GET";
        }

        /**
         * @var Route[] $routes
         */
        $routes = RouteCollector::get(strtoupper($method));


        ///test/30/edit/10 => [test 30 edit 50]
        $parameters = self::reformatArray(explode('/', $url));


        foreach ($routes as $route) {

            $path = $route->getPath();


            $matched = true;
            $prams = [];
            ///home/(?page)
            $expectedParameters = self::getExpectedParameters($path);

            for ($i = 0; $i < count($parameters); $i++) {
                $parameter = $parameters[$i];
                $ex = $expectedParameters[$i] ?? null;
                if (
                    ($ex === null)
                    ||
                    ($ex instanceof RoutePrefixParameter && !$ex->equalsTo($parameter))
                    ||
                    ($ex instanceof RouteRegexParameter && !$ex->isOptional() && !$ex->match($parameter))
                ) {
                    $matched = false;
                    break;
                } else{
                    if($ex instanceof RouteRegexParameter && $ex->match($parameter))
                    {
                        $prams[$ex->getName()] = $parameter;
                    }
                    else if($ex instanceof RouteParameter)
                    {
                        $prams[$ex->getName()] = $parameter;
                    }
                    else if(!$ex instanceof RoutePrefixParameter)
                    {
                        $matched = false;
                        break;
                    }

                }


            }
            while ($i < count($expectedParameters) && $matched) {
                if (!$expectedParameters[$i]->isOptional())
                    $matched = false;
                else {
                    $prams[$expectedParameters[$i]->getName()] = null;
                }
                $i++;
            }
            if ($matched) {
                return self::generateRouteResult($route, $prams);
            }

        }
        return null;

    }


    private static function generateRouteResult(Route $route, array $parameters = [])
    {

        return new RouteResult(
            $route->getName(),
            $route->getController(),
            $parameters,
            $route->getMethod()
        );
    }

    public static function reformatArray($parts)
    {
        $r = [];
        foreach (array_filter($parts) as $part) {
            $r[] = $part;
        }
        return $r;
    }

    /**
     * @param $actualParameters
     * @return ActualParameter[]
     */
    public static function getActualParameters($actualParameters): array
    {
        $r = [];
        foreach ($actualParameters as $k => $v) {
                $r[] = new ActualParameter($v, $k);
        }
        return $r;
    }

    public static function getExpectedParameters($path)
    {
        $parts = explode('/', $path);
        $parameters = [];
        foreach (array_filter($parts) as $part) {
            if (Parameter::hasRegex($part)) {
                $parameters[] = new RouteRegexParameter($part);
            } else if (Parameter::hasParameter($part)) {
                $parameters[] = new RouteParameter($part);
            } else {
                $parameters[] = new RoutePrefixParameter($part);
            }
        }
        return $parameters;
    }
}