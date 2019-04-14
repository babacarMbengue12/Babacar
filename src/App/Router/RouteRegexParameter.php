<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 16:35
 */

namespace Babacar\Router;


class RouteRegexParameter extends Parameter
{
    /**
     * @var string $pattern
     */
    private $pattern;



    public function __construct(?string $variable,$key = null)
   {
       $variable = join('',array_map('trim',explode(' ',$variable)));
       if(self::hasRegex($variable)){
           $start = strpos($variable,'<');
           $end = strpos($variable,'>');
           $this->pattern = substr($variable,$start+1,$end-$start-1);
       }
       parent::__construct($variable,$key);
   }

   public function match(?string $subject){
          return preg_match('/^'.$this->pattern.'$/',$subject);
   }
    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }
    public function setName($variable)
    {

        $variable = str_replace('<'.$this->pattern.'>','',$variable);
        parent::setName($variable);
    }
}