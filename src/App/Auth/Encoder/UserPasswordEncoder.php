<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 06/12/2018
 * Time: 22:24
 */

namespace Babacar\Auth\Encoder;


class UserPasswordEncoder
{


    public function encode(string $password)
    {

        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

    }//end encode()


    public function verify(string $password, string $hashedPassword)
    {

        return password_verify($password, $hashedPassword);

    }//end verify()


}//end class
