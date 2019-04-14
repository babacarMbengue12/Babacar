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
use Babacar\Response\RedirectResponse;
use Babacar\Router\{
    Router, RouteResult
};
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
class TrailingSlashMiddleware implements MiddlewareInterface
{


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $uri = $request->getUri()->getPath();
        if (!empty($uri) && strlen($uri) > 1 &&  $uri[-1] === '/') {
            return new RedirectResponse(substr($uri, 0, -1));
        }

        return $handler->handle($request);

    }//end process()


}//end class
