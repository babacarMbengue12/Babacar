<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 23:45
 */

namespace Babacar\QueryBuilder;


class PaginatedQuery
{
    /**
     * @var QueryBuilder
     */
    private $builder;


    public function __construct(QueryBuilder $builder)
    {
          $this->builder = $builder;

    }//end __construct()


    public function count():int
    {
        return $this->builder->count();

    }//end count()


    public function getResult(int $offset, int $length)
    {
        $query = clone $this->builder;

        return $query->limit($offset, $length)->fetchAll();

    }//end getResult()


}//end class
