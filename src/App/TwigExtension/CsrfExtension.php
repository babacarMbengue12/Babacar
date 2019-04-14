<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27/06/2018
 * Time: 14:47
 */

namespace Babacar\TwigExtension;


use Babacar\Middleware\CsrfMiddleware;

class CsrfExtension extends \Twig_Extension
{


    /**
     * @var CsrfMiddleware
     */
    private $middleware;


    public function __construct(CsrfMiddleware $middleware)
    {

        $this->middleware = $middleware;

    }//end __construct()


    public function getFunctions()
    {
        return [new \Twig_SimpleFunction('csrf_token', [$this, 'csrf'], ['is_safe' => ['html']])];

    }//end getFunctions()


    public function csrf()
    {
        return '<input type="hidden" name="'.$this->middleware->getFormKey().'" value="'.$this->middleware->generateToken().'" >';

    }//end csrf()


}//end class
