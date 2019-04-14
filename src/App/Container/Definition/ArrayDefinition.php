<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 05/12/2018
 * Time: 23:11
 */

namespace Babacar\Container\Definition;


class ArrayDefinition
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $arrays;
    /**
     * @var boolean
     */
    private $isSingleTon;
    /**
     * @var boolean
     */
    private $removable;


    public function __construct(array $arrays, bool $isSingleTon=true, bool $removable=false)
    {
        $this->arrays      = $arrays;
        $this->isSingleTon = $isSingleTon;
        $this->removable   = $removable;

    }//end __construct()


    public function isSingleTon(): bool
    {
        return $this->isSingleTon;

    }//end isSingleTon()


    public function add(array $arrays)
    {
        $this->arrays = array_merge($this->arrays, $arrays);

    }//end add()


    public function isRemovable():bool
    {
        return $this->removable;

    }//end isRemovable()


    public function get()
    {
        return $this->arrays;

    }//end get()


    public function getName(): ?string
    {
        return $this->name;

    }//end getName()


    public function setName(string $name)
    {
        $this->name = $name;

        return $this;

    }//end setName()


}//end class
