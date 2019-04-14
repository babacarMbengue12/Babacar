<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 02/12/2018
 * Time: 01:38
 */

namespace Babacar\TwigExtension;


use Babacar\Router\Router;

class RouterExtension extends \Twig_Extension
{


    public function getFunctions()
    {
         return [new \Twig_SimpleFunction("path", [$this, 'path'])];

    }//end getFunctions()


    public function path(string $name, array $prams=[])
    {
            return Router::generateUri($name, $prams);
    }//end path()


}//end class
