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
class MethodMiddleware implements MiddlewareInterface
{


    /**
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() == "POST") {
            $parsedBody = $request->getParsedBody();
            if (isset($parsedBody['_method'])) {
                $request = $request->withMethod($parsedBody['_method']);
            }
        }

        return $handler->handle($request);

    }//end process()


}//end class
