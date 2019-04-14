<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/04/2019
 * Time: 19:16
 */

namespace Babacar\Router;


class ActualParameter
{
    /**
     * @var string
     */
    private $value;
    /**
     * @var string
     */
    private $key;

    public function __construct(?string $value, ?string $key)
   {
       $this->value = $value;
       $this->key = $key;
   }

    /**
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

}