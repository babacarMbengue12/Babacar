<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/04/2019
 * Time: 14:43
 */

namespace Babacar\Router;


class Route
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $method;
    /**
     * @var null|string
     */
    private $name;

    public function __construct(string $path, $action, string $method, ?string $name=null)
      {

          $this->path = $path;
          $this->controller = $action;
          $this->method = $method;
          $this->name = $name;
      }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Route
     */
    public function setPath(string $path): Route
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param $action
     * @return Route
     */
    public function setController($action): Route
    {
        $this->controller = $action;
        return $this;
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
     * @return Route
     */
    public function setMethod(string $method): Route
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return Route
     */
    public function setName(?string $name): Route
    {
        $this->name = $name;
        return $this;
    }

}