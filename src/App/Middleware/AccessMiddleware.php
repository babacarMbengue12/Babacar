<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 16:28
 */

namespace Babacar\Middleware;


use Babacar\Auth\Auth;

use Babacar\Message\Flash;
use Babacar\Response\RedirectResponse;


use Babacar\Router\RouteResult;
use Babacar\TwigExtension\AuthExtension;

use Psr\Http\Message\{
    ResponseInterface, ServerRequestInterface
};
use Psr\Http\Server\{
    MiddlewareInterface, RequestHandlerInterface
};

/**
 * Class RouterMiddleware
 *
 * @package App\Middleware
 */
class AccessMiddleware implements MiddlewareInterface
{

    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var AuthExtension
     */
    private $extension;
    /**
     * @var Flash
     */
    private $flash;


    public function __construct(Auth $auth, AuthExtension $extension, Flash $flash)
    {
        $this->auth      = $auth;
        $this->extension = $extension;
        $this->flash     = $flash;

    }//end __construct()


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $uri   = $request->getUri()->getPath();
        $user  = $this->auth->getUser();
        //$roles = !is_null($user) ? $user->getRoles() : [];
        $route = $request->getAttribute(RouteResult::class);

        if (/*$this->extension->allowed($roles, $uri) || is_null($route)*/$user || $uri === "/login") {
            return $handler->handle($request);
        }

        $this->flash->error('Vous devez Posseder une Compte Pour Acceder A cette Page');
        return new RedirectResponse('/login');

    }//end process()


}//end class
