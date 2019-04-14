<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 06/12/2018
 * Time: 22:36
 */

namespace Babacar\Session;


class Session implements \ArrayAccess
{


    public function get($offset, $default=null)
    {
        if ($this->has($offset)) {
            return  $this->offsetGet($offset);
        }

        return $default;

    }//end get()


    public function set($offset, $value)
    {
        $this->offsetSet($offset, $value);

    }//end set()


    public function has($offset)
    {
        return $this->offsetExists($offset);

    }//end has()


    public function delete($offset)
    {
        $this->offsetUnset($offset);

    }//end delete()


    public function offsetExists($offset)
    {
        $this->ensureStarted();
        return isset($_SESSION[$offset]);

    }//end offsetExists()


    public function offsetGet($offset)
    {
        $this->ensureStarted();
        if ($this->offsetExists($offset)) {
            return $_SESSION[$offset];
        }

        return null;

    }//end offsetGet()


    public function offsetSet($offset, $value)
    {
        $this->ensureStarted();
        $_SESSION[$offset] = $value;

    }//end offsetSet()


    public function offsetUnset($offset)
    {
        $this->ensureStarted();
        if ($this->offsetExists($offset)) {
            unset($_SESSION[$offset]);
        }

    }//end offsetUnset()


    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

    }//end ensureStarted()


}//end class
