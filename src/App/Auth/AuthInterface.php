<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 06/12/2018
 * Time: 23:06
 */

namespace Babacar\Auth;


use Babacar\Auth\User\User;

interface AuthInterface
{


    /**
     * @return User|null
     */
    public function getUser():?User;


    /**
     * @param  User $user
     * @return bool|User
     */
    public function login(User $user);


    /**
     *
     * @return bool
     */
    public function logout():bool;


}//end interface
