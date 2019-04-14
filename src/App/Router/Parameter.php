<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 16:35
 */

namespace Babacar\Router;


class Parameter
{
    /**
     * @var string
     */
    protected $variable;


    /**
     * @var bool
     */
    protected $isOptional = false;

    /**
     * @var $name string
     */
    protected $name;

    protected $key;

    public function __construct(string $variable,$key = null)
   {
       $variable = preg_replace('/\s/','',$variable);
       $this->variable = $variable;

       $this->isParameter = !empty($variable[0]) && $variable[0] === '(';

       $this->setName($variable);

       $this->isOptional = isset($variable[1]) && $variable[1] === '?' ? true : false;

       $this->key = $key;
   }
    public static function hasRegex(string $var):bool{
        return (strpos($var,'<') !== false && strpos($var,'>') !== false);
    }
    public static function hasParameter(string $var):bool{
        return isset(trim($var)[0]) && trim($var)[0] === '(';
    }


   public function isOptional(){
        return $this->isOptional;
   }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName($variable)
    {
        $variable = str_replace('?','',$variable);
        $variable = str_replace(')','',$variable);
        $variable = str_replace('(','',$variable);
        $this->name = $variable;
    }

    /**
     * @return bool
     */
    public function isParameter(): bool
    {
        return $this->isParameter;
    }

    /**
     * @return null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param null $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }
}