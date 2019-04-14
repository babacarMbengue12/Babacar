<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 02/12/2018
 * Time: 02:11
 */

namespace Babacar\Response;


use GuzzleHttp\Psr7\Response;

class RedirectResponse extends Response
{


    public function __construct(string $route,int $code=301)
    {
        parent::__construct($code, ['location' => $route]);

    }//end __construct()


}//end class
