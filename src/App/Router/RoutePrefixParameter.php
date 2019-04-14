<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 16:35
 */

namespace Babacar\Router;


class RoutePrefixParameter extends Parameter
{
    /**
     * @var string $pattern
     */
    private $value;



    public function __construct(string $variable,$key = null)
   {
       parent::__construct($variable,$key);

       $this->value = $this->getName();
   }

   public function equalsTo(string $value){
        return $value === $this->value;
   }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}