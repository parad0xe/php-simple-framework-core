<?php


namespace Parad0xeSimpleFramework\Core\http;


class Uri
{
    private string $uri_queried;

    private string $uri;

    private array $exploded_uri;

    private array $uri_parameters = [];

    public function __construct(string $uri)
    {
        $this->uri_queried = $uri;

        $query = explode("?", $this->uri_queried);

        $this->__parseUri($query[0]);

        if(count($query) === 2)
            $this->__parseParameters($query[1]);
    }

    /**
     * @return string
     */
    public function getUriQueried(): string
    {
        return $this->uri_queried;
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
    public function getExplodedUri(): array
    {
        return $this->exploded_uri;
    }

    /**
     * @return array
     */
    public function getUriParameters(): array
    {
        return $this->uri_parameters;
    }

    /**
     * @param $parameters
     */
    public function addUriParameters($parameters) {
        if(is_array($parameters))
            $this->uri_parameters = array_merge($this->uri_parameters, $parameters);
        else
            $this->uri_parameters[] = $parameters;
    }

    /**
     * @param $uri
     */
    private function __parseUri($uri) {
        $uri = trim($uri, "/");

        $exploded_uri = explode("/", $uri);
        $exploded_uri = ($exploded_uri[0] === "") ? [] : $exploded_uri;

        if(count($exploded_uri) == 0) {
            $url_data[0] = "errors";
            $url_data[1] = "404";
        } else $url_data = $exploded_uri;

        $this->uri = "/" . implode("/", $url_data);
        $this->exploded_uri = $exploded_uri;
    }

    /**
     * @param $parameters
     */
    private function __parseParameters($parameters) {
        $this->uri_parameters = array_reduce(explode("&", $parameters), function($a, $v) {
            $arg = explode("=", $v);
            $a[$arg[0]] = $arg[1];
            return $a;
        }, []);
    }
}
