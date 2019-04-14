<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 16:28
 */

namespace Babacar\Middleware;



use Babacar\Container\Container;
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
class DispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var Container
     */
    private $container;


    public function __construct(Container $container)
    {

         $this->container = $container;

    }//end __construct()


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        /*
         * @var $route RouteResult
         */
        $route = $request->getAttribute(RouteResult::class);

        if (is_null($route)) {

            return $handler->handle($request);
        }

        $response = call_user_func_array($this->container->get($route->getAction()), [$request]);

        if (is_string($response)) {
            return new Response(200, [], $response);
        }

        return $response;

    }//end process()


}//end class
