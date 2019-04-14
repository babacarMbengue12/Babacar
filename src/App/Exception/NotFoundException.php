<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 03/12/2018
 * Time: 12:08
 */

namespace Babacar\Exception;


use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{


    public function __construct(string $message="", int $code=0, Throwable $previous=null)
    {
        $message = "la  cle $message Non Trouver";
        parent::__construct($message, $code, $previous);

    }//end __construct()


}//end class
