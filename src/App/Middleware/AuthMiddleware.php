<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 16:28
 */

namespace Babacar\Middleware;


use Babacar\Container\{
    Container, Definition\FactoryDefinition
};
use Babacar\Controller\BaseController;
use Babacar\Controller\Login\LoginController;
use Babacar\Response\RedirectResponse;
use Babacar\Router\{
    Router, RouteResult
};
use Babacar\Session\Session;
use GuzzleHttp\Psr7\Response;
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
class AuthMiddleware implements MiddlewareInterface
{

    /**
     * @var Session
     */
    private $session;


    public function __construct(Session $session)
    {
        $this->session = $session;

    }//end __construct()


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $uri = $request->getUri()->getPath();
        if (strpos($uri, '/admin') === 0) {
            if (!$this->session->has('user')) {
                $this->session->set('redirect.route', $uri);
                return new RedirectResponse('/login');
            }
        }

        return $handler->handle($request);

    }//end process()


}//end class
