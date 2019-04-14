<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 16:28
 */

namespace Babacar\Middleware;



use App\Controller\HomeController;
use Babacar\Container\Container;
use Babacar\Response\RedirectResponse;

use Babacar\Router\Router;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
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
class NotFoundMiddleware implements MiddlewareInterface
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

        $request = $request->withUri(new Uri('/Erreur404'));
        return new Response(404,[],$this->container->get(HomeController::class)->NotFound($request));

    }//end process()


}//end class
