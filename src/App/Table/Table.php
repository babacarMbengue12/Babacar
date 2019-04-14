<?php
/**
 * Created by PhpStorm.
 * User: babacar mbengue
 * Date: 12/09/2018
 * Time: 20:11
 */

namespace Babacar\Table;


use Babacar\Container\Container;
use Babacar\QueryBuilder\QueryBuilder;
use Babacar\QueryBuilder\QueryResults;
use \stdClass;

class Table
{
    /**
     * @var \PDO
     */
    protected $pdo;



    protected $entity = StdClass::class;

    protected $table;
    /**
     * @var Container
     */
    protected $container;


    public function __construct(\PDO $pdo, Container $container)
    {

        $this->pdo       = $pdo;
        $this->container = $container;

    }//end __construct()


    /**
     * @return QueryResults
     */
    public function all()
    {
        return $this->makeQuery()->fetchAll();

    }//end all()


    public function insert(array $prams)
    {
        if(!empty($prams))
        {
            $fields = $this->getFields($prams);
            $query  = $this->pdo->prepare("INSERT INTO {$this->table} SET $fields");

            return $query->execute($prams);
        }

        return false;


    }//end insert()


    public function edit(array $prams, int $id)
    {
        if(!empty($prams))
        {
            $fields      = $this->getFields($prams);
            $query       = $this->pdo->prepare("UPDATE {$this->table} SET $fields WHERE id=:id");
            $prams["id"] = $id;
            return $query->execute($prams);
        }
        return false;

    }//end edit()


    /**
     * @param  int $id
     * @return null|object|string
     * @throws \ReflectionException
     */
    public function find(int $id)
    {
        return $this->makeQuery()
            ->where('id = :id')
            ->setParameter('id', $id)
            ->fetch();

    }//end find()

    public function getList(string $key,string $value,$label = null){
        $items = $this->all();
        $list = [];
        if($label) $list['first']=$label;
        foreach ($items as $item)
        {
            $list[$item->{'get'.ucfirst($key)}()] = $item->{'get'.ucfirst($value)}();
        }
        return $list;
    }


    public function count()
    {

        return $this->makeQuery()->count();

    }//end count()


    public function getLastInsertedId()
    {
        $record =$this->pdo->query("SELECT id FROM $this->table ORDER BY id DESC")->fetch(\PDO::FETCH_NUM);
        if(empty($record))
        {
            return 0;
        }
        return (int)$record[0];

    }//end getLastInsertedId()


    protected function getFields(array $prams):string
    {
        return join(
            ",",
            array_map(
                function ($key) {
                    return "$key=:$key";
                },
                array_keys($prams)
            )
        );

    }//end getFields()


    public function delete(int $id)
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $query->execute([$id]);

    }//end delete()


    public function makeQuery(?string $table=null)
    {

        if(!$table){
            $table = $this->table;
            $alias = $this->table[0];
        }
        else{
            $alias=$table[0];
        }
        return (new QueryBuilder($this->pdo, $this->container))->from($table, $alias)->into($this->entity);

    }//end makeQuery()

    public function findBy(string $field, $value)
    {
        return $this->makeQuery()
            ->where("$field = :$field")
            ->setParameter($field, $value)
            ->fetch();

    }//end findBy()


    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;

    }//end getPdo()



}//end class
