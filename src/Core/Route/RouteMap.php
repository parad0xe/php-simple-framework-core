<?php


namespace Parad0xeSimpleFramework\Core\Route;


use Parad0xeSimpleFramework\Core\ApplicationContext;
use Parad0xeSimpleFramework\Core\Response\RedirectResponse;
use stdClass;

class RouteMap
{
    /**
     * @var Route[]
     */
    private array $_route_map = [];

    private array $_route_map_by_method = [];

    public function __construct(ApplicationContext $context)
    {
        $this->__load($context);
    }

    /**
     * @param string $route_name
     * @return Route|null
     */
    public function get(string $route_name) {
        if(array_key_exists($route_name, $this->_route_map)) {
            return $this->_route_map[$route_name];
        }

        return null;
    }

    /**
     * @param string $route_name
     * @param array $args
     * @return string
     */
    public function generate(string $route_name, array $args = []): string {
        $copy_args = $args;

        if(array_key_exists($route_name, $this->_route_map)) {
            $uri = $this->_route_map[$route_name]->getUri();

            foreach ($copy_args as $key => $arg) {
                if(strpos($uri, ":$key") !== false) {
                    $uri = str_replace(":$key", $arg, $uri);
                    unset($copy_args[$key]);
                }
            }

            return explode("/:", $uri)[0] . $this->__urlParamsEncode($copy_args);
        }

        return "";
    }

    /**
     * @param string $route_name
     * @param array $params
     * @return string|null
     */
    public function url(string $route_name, array $params = []) {
        return $this->generate($route_name, $params);
    }

    /**
     * @param string $uri
     * @param array $params
     * @return RedirectResponse
     */
    public function redirect(string $uri, array $params = []): RedirectResponse {
        return new RedirectResponse($uri . $this->__urlParamsEncode($params));
    }

    /**
     * @param string $route_name
     * @param array $params
     * @return RedirectResponse
     */
    public function redirectTo(string $route_name, array $params = []): RedirectResponse {
        return new RedirectResponse($this->generate($route_name, $params));
    }

    /**
     * @param string|null $method
     * @return Route[]
     */
    public function all(?string $method = null): array {
        if($method) {
            $method = strtoupper($method);
            return (array_key_exists($method, $this->_route_map_by_method)) ? $this->_route_map_by_method[$method] : [];
        }
        return $this->_route_map;
    }

    /**
     * Map all the routes accessible from the controllers
     * @param ApplicationContext $context
     */
    private function __load(ApplicationContext $context) {
        $controllers_path = $context->config()->get("app.directory.root") . "/src/Controller";

        if(!file_exists($controllers_path)) return;

        $controller_classnames = array_reduce(array_slice(scandir($controllers_path), 2), function($a, $v) {
            if(endsWith($v, "Controller.php"))
                $a[] = "App\\Controller\\" . explode('.', $v)[0];
            return $a;
        }, []);

        array_map(function($v) {
            $controller_name = str_replace("Controller", "", array_slice(explode('\\', $v), -1, 1)[0]);
            $lower_controller_name = strtolower($controller_name);
            $rc = new \ReflectionClass($v);

            foreach ($rc->getMethods() as $method) {
                if($method->isPublic() && !startsWith($method->getName(), "__")) {
                    $attributes = $method->getAttributes(Route::class);

                    $methods = $method->getAttributes(RouteMethod::class);
                    $methods = (empty($methods)) ? ["GET"] : $methods[0]->getArguments();
                    $methods = array_map(function($v) { return strtoupper($v); }, $methods);

                    foreach ($attributes as $attribute) {
                        $route_name = $lower_controller_name . ":" . $method->getName();

                        $name = $attribute->getArguments()[0] ?? $route_name;
                        $url = $attribute->getArguments()[1] ?? "/$lower_controller_name/{$method->getName()}";
                        $parameters = $attribute->getArguments()[2] ?? [];
                        $controller = $v;
                        $action = $method->getName();

                        $route = (new Route($name, $url, $parameters))
                            ->setController($controller)
                            ->setAction($action)
                            ->setMethods($methods);

                        $this->_route_map[$name] = $route;

                        foreach ($methods as $method)
                            $this->_route_map_by_method[$method][$name] = $route;
                    }
                }
            }
        }, $controller_classnames);

        $this->_route_map["root"] = (new Route("root", "/"))->setMethods(["GET"]);
        $this->_route_map_by_method["GET"]["root"] = $this->_route_map["root"];
    }

    /**
     * @param array $params
     * @param bool $with_start_char
     * @return string
     */
    private function __urlParamsEncode(array $params, bool $with_start_char = true) {
        $url_args = array_map(function($k) use ($params) {
            return "$k=" . $params[$k];
        }, array_keys($params));

        if(count($url_args))
            return (($with_start_char) ? "?" : "") . implode("&", $url_args);

        return "";
    }
}
