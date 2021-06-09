<?php


namespace Parad0xeSimpleFramework\Core\Route;


class Route
{
    private string $name;

    private string $uri;

    private array $parameters = [];

    private string $controller = "";

    private array $methods = [];

    /**
     * @var string
     */
    private string $action;

    public function __construct(string $name = null, string $uri = null, array $parameters = [])
    {
        $this->name = $name;
        $this->uri = $uri;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     * @return Route
     */
    public function setController(string $controller): Route
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Route
     */
    public function setAction(string $action): Route
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     * @return Route
     */
    public function setMethods(array $methods): Route
    {
        $this->methods = $methods;
        return $this;
    }
}
