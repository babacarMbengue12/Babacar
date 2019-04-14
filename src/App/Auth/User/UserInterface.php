<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 06/12/2018
 * Time: 22:09
 */

namespace Babacar\Auth\User;


interface UserInterface
{


    /**
     * @return null|string
     */
    public function getUsername():?string;


    /**
     * @return \Traversable
     */
    public function getRoles();


}//end interface
