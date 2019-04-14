<?php


namespace Babacar\Middleware;

use Babacar\Exception\CsrfException;
use Babacar\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware implements MiddlewareInterface
{

    /**
     * @var Session
     */
    private $session;

    private $form_key = "_CSRF";

    private $tokens;

    private $session_key = 'CSRF';

    private $limit = 50;


    public function __construct(Session $session)
    {
        $this->session = $session;

    }//end __construct()


    /**
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['DELETE', 'PUT', 'POST',"PATCH"])) {
            $token = ($request->getParsedBody()[$this->form_key] ?? '');
            if (!$this->isValid($token)) {
                throw new CsrfException();
            }

            $this->deleteTokens($token);
        }

        return $handler->handle($request);

    }//end process()


    /**
     * @throws \Exception
     */
    public function generateToken()
    {
        $token        = bin2hex(random_bytes(16));
        $this->tokens = ($this->session[$this->session_key] ?? []);
        $this->limitTokens();
        $this->tokens[] = $token;
        $this->session[$this->session_key] = $this->tokens;

        return $token;

    }//end generateToken()


    /**
     * @return string
     */
    public function getFormKey(): string
    {
        return $this->form_key;

    }//end getFormKey()


    private function limitTokens()
    {

        $this->tokens = ($this->session[$this->session_key] ?? []);

        if (count($this->tokens) >= $this->limit) {
            array_shift($this->tokens);
        }

        $this->session[$this->session_key] = $this->tokens;

    }//end limitTokens()


    private function isValid(string $token)
    {
        $this->tokens = ($this->session[$this->session_key] ?? []);
        return in_array($token, $this->tokens);

    }//end isValid()


    private function deleteTokens(string $token)
    {
        $this->tokens = ($this->session[$this->session_key] ?? []);

        $this->tokens = array_filter(
            $this->tokens,
            function ($t) use ($token) {
                return $t !== $token;
            }
        );

        $this->session[$this->session_key] = $this->tokens;

    }//end deleteTokens()


}//end class
