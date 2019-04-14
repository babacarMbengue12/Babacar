<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 18:21
 */

namespace Babacar\Router;


use Babacar\Router\Exceptions\RouteNotFoundException;
use Babacar\Router\Exceptions\RouteParameterNameException;
use Babacar\Router\Exceptions\RouteParameterNotMatchException;
use Babacar\Router\Exceptions\RouteParametersMissingException;

class RouteGenerator
{

    /**
     * @param       $name
     * @param array $prams
     * @param array $query_args
     * @return array|string
     * @throws RouteNotFoundException
     * @throws RouteParametersMissingException
     * @throws RouteParameterNotMatchException
     * @throws RouteParameterNameException
     */
    public static function generate($name, array $prams = [], array $query_args = [])
    {
       $route = RouteCollector::getByName($name);
       if(is_null($route)){
         throw  new RouteNotFoundException($name);
       }
        /**
         * @var $expectedParameters Parameter[]
         */
      $expectedParameters = RouteMatcher::getExpectedParameters($route->getPath());
      $actualParameters = RouteMatcher::getActualParameters($prams);

      $expected = [];
      $uri = [];
      $posUri = 0;
      $postExpected = 0;
      foreach ($expectedParameters as $parameter){
          if($parameter instanceof RouteRegexParameter || $parameter instanceof RouteParameter){
              $expected[$postExpected++]=$parameter;
              $posUri++;
          }
          else if($parameter instanceof RoutePrefixParameter){
              $uri[$posUri]=$parameter->getValue();
              $posUri++;
          }
      }
       if(empty($expectedParameters)){
           return $route->getPath();
       }
        $actualParameters = self::reorganise($expected,$actualParameters);

       for ($i = 0;$i < $postExpected ;$i++)
       {
           $ex = $expected[$i];
           $ac = $actualParameters[$i] ?? null;
           if($ac === null && !$ex->isOptional())
               throw new RouteParametersMissingException($ex->getName());
           else if($ex instanceof RouteRegexParameter && $ac !== null && !$ex->match($ac->getValue()))
               throw new RouteParameterNotMatchException($ex->getName(),$ex->getPattern(),$ac->getValue());
           else
           {
               if($ac !== null )
               {
                   if($ex->getName() !== $ac->getKey())
                   {
                       throw new RouteParametersMissingException($ex->getName());
                   }
                   $j = 0;
                   while (isset($uri[$j]))
                       $j++;

                   $uri[$j]=$ac->getValue();
               }

           }
       }

       ksort($uri);

       $uri = join("/",$uri);
       if(!empty($query_args)){
           $uri.="?".http_build_query($query_args);
       }
       return "/".$uri;
    }

    /**
     * @param Parameter[] $expecteds
     * @param ActualParameter[] $actualParameters
     */
    private static function reorganise($expecteds, $actualParameters)
    {
        $p = [];
        foreach ($expecteds as $expected)
        {
            foreach ($actualParameters as $parameter)
            {
                if($expected->getName() === $parameter->getKey())
                {
                    $p[]=$parameter;
                    break;
                }

            }
        }

        return $p;
    }
}