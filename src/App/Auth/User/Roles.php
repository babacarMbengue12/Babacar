<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 07/12/2018
 * Time: 12:20
 */

namespace Babacar\Auth\User;


class Roles
{

    private $role;

    private $id;


    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;

    }//end getRole()


    /**
     * @param mixed $role
     *
     * @return Roles
     */
    public function setRole($role): self
    {
        $this->role = $role;

        return $this;

    }//end setRole()


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;

    }//end getId()


    /**
     * @param  mixed $id
     * @return Roles
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;

    }//end setId()


}//end class
