<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 06/12/2018
 * Time: 22:12
 */

namespace Babacar\Auth\User;




use App\QueryBuilder\QueryResults;
use App\Table\RolesTable;
use App\Table\UserTable;

class User implements UserInterface,\Serializable
{

    /**
     * @var string|null;
     */
    private $username;

    /**
     * @var integer|null
     */
    private $id;

    /**
     * @var string|null;
     */
    private $password;






    public function getUsername(): ?string
    {
        return $this->username;

    }//end getUsername()



    public function getRoles()
    {
        return [];
    }


    /**
     * String representation of object
     *
     * @link   http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since  5.1.0
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
            ]
        );

    }//end serialize()


    /**
     * Constructs the object
     *
     * @link   http://php.net/manual/en/serializable.unserialize.php
     * @param  string $serialized <p>
     *                            The string representation of the object.
     *                            </p>
     * @return void
     * @since  5.1.0
     */
    public function unserialize($serialized)
    {
        list($this->id,$this->username,$this->password) = unserialize($serialized, ['allowed_classes' => false]);

    }//end unserialize()


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;

    }//end getId()


    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;

    }//end setId()


    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;

    }//end getPassword()


    /**
     * @param null|string $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;

    }//end setPassword()


    /**
     * @param null|string $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;

    }//end setUsername()




}//end class
