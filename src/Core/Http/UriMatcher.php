<?php


namespace Parad0xeSimpleFramework\Core\Http;

use Parad0xeSimpleFramework\Core\Route\Route;

class UriMatcher
{
    /**
     * @param Uri $uri
     * @param Route $route
     * @return UriMatcherResult|null
     */
    public function match(Uri $uri, Route $route): ?UriMatcherResult
    {
        $route_uri = new Uri($route->getUri());

        $match_data = $this->__parseMatchData($uri, $route, $route_uri);

        $regex = "/^" . str_replace("/", "\/", $match_data['regex_uri']) . "$/";
        $uri_start_with = explode("/:", $uri->getUri())[0];
        $route_uri_start_with = explode("/:", $route_uri->getUri())[0];
        if(
            (
                (startsWith($uri->getUri(), $route_uri_start_with) !== false && strlen($uri->getUri()) <= $route_uri_start_with) ||
                startsWith($uri->getUri() . "/index", $route_uri_start_with) !== false
            ) &&
            preg_match($regex, $match_data['uri']) &&
            strpos($route_uri_start_with, $uri_start_with) !== false
        ) {
            $route_uri->addUriParameters($uri->getUriParameters());
            $route_uri->addUriParameters($match_data["parameters"]);
            return new UriMatcherResult($route, $route_uri, $match_data["parameters"]);
        }

        return null;
    }

    /**
     * @param Uri $uri
     * @param Route $route
     * @param Uri $route_uri
     * @return array
     */
    private function __parseMatchData(Uri $uri, Route $route, Uri $route_uri): array {
        $expected_url_parameter = $route_uri->getExplodedUri();

        return array_reduce(array_keys($expected_url_parameter), function($a, $index) use($uri, $route_uri, $expected_url_parameter, $route) {
            if(strpos($expected_url_parameter[$index], ":") !== false) {
                $expected_parameter = substr($expected_url_parameter[$index], 1);
                if(
                    array_key_exists($expected_parameter, $route->getParameters()) &&
                    array_key_exists($index, $uri->getExplodedUri()) &&
                    $uri->getExplodedUri()[$index] !== null
                ) {
                    if(array_key_exists("regex", $route->getParameters()[$expected_parameter])) {
                        $a["uri"] = str_replace($expected_url_parameter[$index], $uri->getExplodedUri()[$index], $a["uri"]);
                        $a["regex_uri"] = str_replace($expected_url_parameter[$index], $route->getParameters()[$expected_parameter]["regex"], $a["regex_uri"]);
                        $a["parameters"][$expected_parameter] = $uri->getExplodedUri()[$index];
                    }
                } elseif(array_key_exists($expected_parameter, $route->getParameters()) && array_key_exists("default", $route->getParameters()[$expected_parameter])) {
                    $a["uri"] = str_replace($expected_url_parameter[$index], $route->getParameters()[$expected_parameter]["default"], $a["uri"]);
                    $a["regex_uri"] = str_replace($expected_url_parameter[$index], $route->getParameters()[$expected_parameter]["default"], $a["regex_uri"]);
                    $a["parameters"][$expected_parameter] = $route->getParameters()[$expected_parameter]["default"];
                }
            }
            return $a;
        }, ["parameters" => [], "regex_uri" => $route->getUri(), "uri" => $route->getUri()]);
    }
}
