<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 18:38
 */

namespace Babacar\Router\Exceptions;


use Throwable;

class RouteParametersMissingException extends RouteException
{


    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Le parametre %s est obligatoir",$message), $code, $previous);
    }
}