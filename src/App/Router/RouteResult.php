<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 16:25
 */

namespace Babacar\Router;


class RouteResult
{


    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string
     */
    private $action;
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var string
     */
    private $method;

    public function __construct(?string $name, $action, array $parameters, string $method)
    {
        $this->name = $name;
        $this->action = $action;
        $this->parameters = $parameters;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $parameters
     */
    public function setParameters(string $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

}