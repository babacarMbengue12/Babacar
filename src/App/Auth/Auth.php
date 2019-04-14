<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 06/12/2018
 * Time: 23:10
 */

namespace Babacar\Auth;


use Babacar\Auth\Encoder\UserPasswordEncoder;
use Babacar\Auth\User\User;
use Babacar\Auth\User\UserInterface;
use Babacar\Session\Session;
use App\Table\RolesTable;
use App\Table\UserTable;

class Auth implements AuthInterface
{

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var UserTable
     */
    private $table;



    public function __construct(UserPasswordEncoder $encoder, Session $session, UserTable $table)
    {
        $this->encoder    = $encoder;
        $this->session    = $session;
        $this->table      = $table;

    }//end __construct()


    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->session->has('user')) {
            $user = new User();
            $user->unserialize($this->session->get('user'));

            return $user;
        }

        return null;

    }//end getUser()


    /**
     * @param  User $user
     * @return bool|User
     */
    public function login(User $U)
    {
        /*
         * @var $user User|null
         */
        $user = $this->table->findBy('username', $U->getUsername());

        if ($user && $this->encoder->verify($U->getPassword(), $user->getPassword())) {
            $this->session->set('user', $user->serialize());
            return $user;
        }

        return false;

    }//end login()


    /**
     *
     * @return bool
     */
    public function logout(): bool
    {
        $this->session->delete('user');
        return true;

    }//end logout()


}//end class
