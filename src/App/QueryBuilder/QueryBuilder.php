<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 17:27
 */

namespace Babacar\QueryBuilder;


use Babacar\Container\Container;
use Babacar\Exception\NoRecordsException;

class QueryBuilder
{
    /**
     * @var array $from
     */
    private $from = [];

    private $entity = \stdClass::class;

    /**
     * @var array $joins
     */
    private $joins = [];
    /**
     * @var array $selects
     */
    private $selects = [];

    /**
     * @var $limit
     */
    private $limit;

    /**
     * @var array $order
     */
    private $order = [];

    /**
     * @var array $where
     */
    private $where = [];
    /**
     * @var \PDO
     */
    private $PDO;

    private $parameters = [];

    private $groupBy = [];
    /**
     * @var Container
     */
    private $container;

    private $sql;


    public function __construct(\PDO $PDO, Container $container)
    {
        $this->PDO       = $PDO;
        $this->container = $container;

    }//end __construct()


    public function count()
    {

        $query = clone $this;
        $query->select("count(".current($this->from)[0].".id)");

        return $query->getQuery()->fetchColumn();

    }//end count()
    public function fetchAssoc(){
        return $this->getQuery()->fetchAll();
    }



    /**
     * @return QueryResults
     */
    public function fetchAll()
    {
        $res = $this->getQuery()->fetchAll();


        return (new QueryResults($res, $this->entity));

    }//end fetchAll()


    /**
     *
     *
     * @throws \ReflectionException
     */
    public function fetch()
    {

        $result = $this->getQuery()->fetch();
        if(is_array($result))
        $result = array_filter($result);

        if ($result && !empty($result)){
            return Hydrator::hydrate($result, $this->entity);
        }

        return null;

    }//end fetch()


    public function insert($obj)
    {
        if (is_object($obj)) {
            $obj = Hydrator::DisHydrate($obj);
        }

        $field = join(
            ", ",
            array_map(
                function ($key) {
                    return "$key = :$key";
                },
                array_keys($obj)
            )
        );

        $from = array_keys($this->from);
        if (!is_string($from[0])) {
            $from = $this->from[0];
        } else {
            $from = $from[0];
        }

        $parts = "INSERT INTO $from SET $field";

        $this->setParameters($obj);

        return $this->update($parts);

    }//end insert()


    public function update(string $sql)
    {
        $stm = $this->PDO->prepare($sql);
        if (!empty($this->parameters)) {
            return $stm->execute($this->parameters);
        }

           return $stm->execute();

    }//end update()


    public function fetchHydrate()
    {

        $results = $this->getQuery()->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($results)) {
            $returns = [];
            foreach ($results as $k => $result) {
                $returns[] = Hydrator::hydrate($result, $this->entity);
            }

            return $returns;
        }

        return [];

    }//end fetchHydrate()


    /**
     *
     * @throws NoRecordsException
     * @throws \ReflectionException
     */
    public function fetchOrFail()
    {

        $result = $this->getQuery()->fetch();
        if ($result) {
            return Hydrator::hydrate($result, $this->entity);
        }

        throw  new NoRecordsException();

    }//end fetchOrFail()


    public function setParameters(array $parameters):self
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;

    }//end setParameters()


    public function setParameter(string $parameter, $value):self
    {
        $this->parameters[$parameter] = $value;

        return $this;

    }//end setParameter()


     public function groupBy(string $fields):self{
        $this->groupBy[] = $fields;

        return $this;
     }


    public function where(string ...$condition):self
    {

        $this->where[0][0] = $condition;

        return $this;

    }//end where()


    public function andWhere(string ...$condition):self
    {

        $this->where["AND"][] = $condition;

        return $this;

    }//end andWhere()


    public function orWhere(string ...$condition):self
    {

        $this->where["OR"][] = $condition;

        return $this;

    }//end orWhere()


    public function limit(int $offset, ?int $length=null):self
    {
        if (!is_null($length)) {
            $this->limit = "LIMIT $offset, $length";
        } else {
            $this->limit = "LIMIT $offset";
        }

        return $this;

    }//end limit()


    public function order(string $field, string $dir="DESC")
    {
        $this->order[$field] = $dir;
        return $this;

    }//end order()


    public function select(string ...$selects):self
    {
        $this->selects = $selects;

        return $this;

    }//end select()


    public function andSelect(string ...$selects):self
    {
        $this->selects = array_merge($this->selects, $selects);

        return $this;

    }//end andSelect()


    public function leftJoin(string $table, string $condition):self
    {
        $this->joins["LEFT"][] = [
            $table,
            $condition,
        ];

        return $this;

    }//end leftJoin()


    public function rightJoin(string $table, string $condition):self
    {
        $this->joins["RIGHT"][] = [
            $table,
            $condition,
        ];

        return $this;

    }//end rightJoin()


    public function innerJoin(string $table, string $condition):self
    {
        $this->joins["INNER"][] = [
            $table,
            $condition,
        ];

        return $this;

    }//end innerJoin()


    public function from(string $from, ?string $alias=null):self
    {
        if (is_null($alias)) {
            $this->from[] = $from;
        } else {
            $this->from[$from] = $alias;
        }

        return $this;

    }//end from()


    public function __toString()
    {
        $parts   = [];
        $parts[] = "SELECT";
        if (empty($this->selects)) {
            $parts[] = "*";
        } else {
            $parts[] = join(", ", $this->selects);
        }

        $parts[] = $this->buildFrom();
        if (!empty($this->joins)) {
            foreach ($this->joins as $type => $join) {
                foreach ($join as [$table, $condition]) {
                    $parts[] = "$type JOIN $table ON $condition";
                }
            }
        }

        if (!empty($this->where)) {
            $parts[] = $this->buildWhere();
        }
        if(!empty($this->groupBy)){
            $parts[]="GROUP BY ";
            $parts[]=join(', ',$this->groupBy);
        }
        if (!empty($this->order)) {
            $parts[] = "ORDER BY";
            foreach ($this->order as $field => $dir) {
                $order[] = "$field $dir";
            }

            $parts[] = join(", ", $order);
        }

        if ($this->limit) {
            $parts[] = $this->limit;
        }

        return join(' ', $parts);

    }//end __toString()


    public function into(string $entity):self
    {
        $this->entity = $entity;
        return $this;

    }//end into()


    private function buildFrom()
    {
         $returns = ['FROM'];
        foreach ($this->from as $from => $alias) {
            if (is_string($alias)) {
                $returns[] = "$from as $alias";
            } else {
                $returns[] = $from;
            }
        }

         return join(" ", $returns);

    }//end buildFrom()

    public function getQueryString(){
        if(!$this->sql){
            $this->sql = $this->__toString();

        }
        return $this->sql;
    }
    public function setQueryString(string $sql):self{
        $this->sql = $sql;
        return $this;
    }
    public function getQuery()
    {
        $query = $this->getQueryString();
//       echo "<br>";
//       echo "<br>";
//       echo "<br>";
//       echo "<br>";
//       var_dump($query);
        $stm   = $this->PDO->prepare($query);
        if (empty($this->parameters)) {
            $stm->execute();
        } else {
            $stm->execute($this->parameters);
        }

        $stm->setFetchMode(\PDO::FETCH_ASSOC);

        return $stm;

    }//end getQuery()


    public function paginate(int $page, int $perPage)
    {
        $query = new PaginatedQuery($this);

        return (new PaginatedResult($query))->setPage($page)->setPerPage($perPage);

    }//end paginate()


    private function buildWhere()
    {
        if (isset($this->where[0][0])) {
            $returns = ["WHERE (".join(" AND ", $this->where[0][0]).")"];
        }

        foreach ($this->where as $type => $wheres) {
            foreach ($wheres as $where) {
                if (is_string($type)) {
                    $returns[] = "$type (".join(" AND ", $where).")";
                }
            }
        }

        return join(" ", $returns);

    }//end buildWhere()


}//end class
