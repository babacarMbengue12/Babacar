<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 23:48
 */

namespace Babacar\QueryBuilder;


use Babacar\QueryBuilder\View\Bs4View;

class PaginatedResult implements \Iterator,\ArrayAccess
{

    private $index = 0;

    private $records;

    private $count = 0;
    /**
     * @var integer
     */
    private $page;
    /**
     * @var integer
     */
    private $perPage;
    /**
     * @var PaginatedQuery
     */
    private $query;


    public function __construct(PaginatedQuery $query)
    {
        $this->query = $query;

    }//end __construct()


    public function render(string $url)
    {
        return (new Bs4View($url, $this->page, $this->perPage, $this->count))->render();

    }//end render()


    /**
     * Return the current element
     *
     * @link   http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since  5.0.0
     */
    public function current()
    {

        $this->ensure();
        return $this->records[$this->index];

    }//end current()


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
        $this->ensure();
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
        $this->ensure();
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
     */
    public function offsetGet($offset)
    {
        $this->ensure();
        return $this->records[$this->index];

    }//end offsetGet()


    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
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
        $this->ensure();
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
        $this->ensure();
        unset($this->records[$offset]);

    }//end offsetUnset()


    /**
     * @param  int $perPage
     * @return PaginatedResult
     */
    public function setPerPage(int $perPage): PaginatedResult
    {
        $this->perPage = $perPage;
        return $this;

    }//end setPerPage()


    /**
     * @param  int $page
     * @return PaginatedResult
     */
    public function setPage(int $page): PaginatedResult
    {
        $this->page = $page;
        return $this;

    }//end setPage()


    private function ensure()
    {
        if (is_null($this->records)) {
            $this->records = $this->query->getResult(
                (($this->page - 1) * $this->perPage),
                $this->perPage
            );
        }

        if ($this->count === 0) {
            $this->count = $this->query->count();
        }

    }//end ensure()


}//end class
