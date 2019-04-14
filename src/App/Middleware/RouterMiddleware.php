<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 16:28
 */

namespace Babacar\Middleware;


use Babacar\Router\RouteCollector;
use Babacar\Router\Router;
use Babacar\Router\RouteResult;
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
class RouterMiddleware implements MiddlewareInterface
{



    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var RouteResult $route
         */
        $route = Router::match($request);

        if (!is_null($route)) {
            foreach ($route->getParameters() as $k => $v) {
                $request = $request->withAttribute($k, $v);
            }

            $request = $request->withAttribute(RouteResult::class, $route);
        }

        return $handler->handle($request);

    }//end process()


}//end class
