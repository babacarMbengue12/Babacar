<?php
namespace Babacar\Router\Exceptions;
use Throwable;

/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 14:56
 */

class DuplicateRouteNameException extends RouteException
{
     public function __construct(string $name = "", int $code = 0, Throwable $previous = null)
     {
         parent::__construct(sprintf("Le route %s existe deja",$name), $code, $previous);
     }
}