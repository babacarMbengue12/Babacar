<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/12/2018
 * Time: 09:01
 */

namespace Babacar\QueryBuilder;



class QueryResults implements \Iterator, \ArrayAccess, \Countable
{

    private $index = 0;

    /**
     * @var array
     */
    private $records;


    /**
     * @var string
     */
    private $entity;
    /**
     * @var array
     */
    private $hydratedRecords = [];


    public function __construct(array $records, string $entity)
    {
        $this->records = $records;

        $this->entity = $entity;


    }//end __construct()


    /**
     * Return the current element
     *
     * @link   http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since  5.0.0
     * @throws \ReflectionException
     */
    public function current()
    {

        if (!isset($this->hydratedRecords[$this->index])) {
            $this->hydratedRecords[$this->index] = Hydrator::hydrate(
                $this->records[$this->index],
                $this->entity
            );
        }

        return $this->hydratedRecords[$this->index];

    }//end current()

    /**
     * @return array
     */
    public function getRecords(): array
    {
        return $this->records;
    }

    /**
     * Move forward to next element
     *
     * @link   http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since  5.0.0
     */
    public function next()
    {
        $this->index++;

    }//end next()


    /**
     * Return the key of the current element
     *
     * @link   http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since  5.0.0
     */
    public function key()
    {
        return $this->index;

    }//end key()


    /**
     * Checks if current position is valid
     *
     * @link   http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since  5.0.0
     */
    public function valid()
    {

        return isset($this->records[$this->index]);

    }//end valid()


    /**
     * Rewind the Iterator to the first element
     *
     * @link   http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since  5.0.0
     */
    public function rewind()
    {
        $this->index = 0;

    }//end rewind()


    /**
     * Whether a offset exists
     *
     * @link   http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param  mixed $offset <p>
     *                       An offset to check for.
     *                       </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since  5.0.0
     */
    public function offsetExists($offset)
    {

        return isset($this->records[$offset]);

    }//end offsetExists()


    /**
     * Offset to retrieve
     *
     * @link   http://php.net/manual/en/arrayaccess.offsetget.php
     * @param  mixed $offset <p>
     *                       The offset to retrieve.
     *                       </p>
     * @return mixed Can return all value types.
     * @since  5.0.0
     * @throws \ReflectionException
     */
    public function offsetGet($offset)
    {

        if (!isset($this->hydratedRecords[$offset])) {
            $this->hydratedRecords[$offset] = Hydrator::hydrate(
                $this->records[$offset],
                $this->entity
            );
        }

        return $this->hydratedRecords[$offset];

    }//end offsetGet()


    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value <p>
     *                      The
     *                      value
     *                      to
     *                      set.
     *                      </p>
     *
     * @return void
     * @since  5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->records[$offset] = $value;

    }//end offsetSet()


    /**
     * Offset to unset
     *
     * @link   http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param  mixed $offset <p>
     *                       The offset to unset.
     *                       </p>
     * @return void
     * @since  5.0.0
     */
    public function offsetUnset($offset)
    {

        unset($this->records[$offset]);

    }//end offsetUnset()


    /**
     * Count elements of an object
     *
     * @link   http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since  5.1.0
     */
    public function count()
    {
        return count($this->records);

    }//end count()


}//end class
