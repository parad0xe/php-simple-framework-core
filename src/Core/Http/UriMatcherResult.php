<?php


namespace Parad0xeSimpleFramework\Core\Http;


use Parad0xeSimpleFramework\Core\Route\Route;

class UriMatcherResult
{
    /**
     * @var Route
     */
    private Route $route;

    /**
     * @var Uri
     */
    private Uri $route_uri;

    /**
     * @var array
     */
    private array $route_parameters = [];


    /**
     * UriMatcherResult constructor.
     * @param Route $route
     * @param Uri $route_uri
     * @param array $route_parameters
     */
    public function __construct(Route $route, Uri $route_uri, array $route_parameters)
    {
        $this->route = $route;
        $this->route_uri = $route_uri;
        $this->route_parameters = $route_parameters;
    }

    /**
     * @return Route
     */
    public function route(): Route
    {
        return $this->route;
    }

    /**
     * @return Uri
     */
    public function routeUri(): Uri
    {
        return $this->route_uri;
    }

    /**
     * @return array
     */
    public function routeParameters(): array
    {
        return $this->route_parameters;
    }
}
