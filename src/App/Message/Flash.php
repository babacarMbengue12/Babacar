<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 07/12/2018
 * Time: 16:43
 */

namespace Babacar\Message;


use Babacar\Session\Session;

class Flash
{
    /**
     * @var Session
     */
    private $session;

    private $message = [];

    private $key = "_FLASH";


    public function __construct(Session $session)
    {
        $this->session = $session;

    }//end __construct()


    public function success(string $message)
    {
        $flash            = $this->session->get($this->key, []);
        $flash['success'] = $message;
        $this->session->set($this->key, $flash);

    }//end success()


    public function error(string $message)
    {
        $flash          = $this->session->get($this->key, []);
        $flash['error'] = $message;
        $this->session->set($this->key, $flash);

    }//end error()


    public function get(string $type)
    {
        if (empty($this->message)) {
            $this->message = $this->session->get($this->key, []);
            $this->session->delete($this->key);
        }

        if (isset($this->message[$type])) {
            return $this->message[$type];
        }

        return null;

    }//end get()


}//end class
