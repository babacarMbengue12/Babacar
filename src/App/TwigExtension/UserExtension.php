<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27/06/2018
 * Time: 19:34
 */

namespace Babacar\TwigExtension;


use Babacar\Auth\Auth;
use Babacar\Message\Flash;
use Babacar\Session\Session;

class UserExtension extends \Twig_Extension
{


    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var Flash
     */
    private $flash;


    public function __construct(Auth $auth, Flash $flash)
    {

        $this->auth  = $auth;
        $this->flash = $flash;

    }//end __construct()


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('user', [$this, 'getUser']),
            new \Twig_SimpleFunction('flash', [$this, 'flash']),
        ];

    }//end getFunctions()


    public function getUser()
    {
        return $this->auth->getUser();

    }//end getUser()


    public function flash(string $type)
    {

        return $this->flash->get($type);

    }//end flash()


}//end class
