<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27/06/2018
 * Time: 19:34
 */

namespace Babacar\TwigExtension;


class TimeAgoExtension extends \Twig_Extension
{


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ago', [$this, 'ago'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('dump', [$this, 'dump'])
        ];

    }//end getFunctions()

    public function dump($var,$die = false)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";

        if($die)
            die();
    }


    public function ago($date, string $format='d/m/Y H:i')
    {

        $date = $this->getValue($date);
        return '<time class="timeago" datetime='.$date->format(\DateTime::ISO8601).'>'.$date->format($format).'</time>';

    }//end ago()


    private function getValue($date)
    {
        if ($date instanceof \DateTime) {
            return $date;
        }

        return new \DateTime($date);

    }//end getValue()


}//end class
