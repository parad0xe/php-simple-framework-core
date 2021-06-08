<?php

namespace Parad0xeSimpleFramework\Core;

use Parad0xeSimpleFramework\Core\http\Uri;
use Parad0xeSimpleFramework\Core\http\UriMatcher;
use Parad0xeSimpleFramework\Core\Request\Request;
use Parad0xeSimpleFramework\Core\Response\EmptyResponse;
use Parad0xeSimpleFramework\Core\Response\RedirectResponse;
use Parad0xeSimpleFramework\Core\Response\Response;
use Parad0xeSimpleFramework\Core\Response\ResponseInterface;
use Exception;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

class Application {
    /**
     * @var string
     */
    private string $_root_directory;

    /**
     * @var ApplicationContext
     */
    private ApplicationContext $_context;

    public function __construct($root)
    {
        $this->_root_directory = rtrim($root, "/");

        require_once __DIR__ . '/../../macro_functions.php';
        session_start();
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function dispatch(Request $request): ResponseInterface
    {
        $this->_context = new ApplicationContext($this->_root_directory, $request);

        $uri = new Uri($request->server()->uri());

        foreach($this->_context->route()->all() as $route) {
            $match_result = (new UriMatcher())->match($uri, $route);

            if($match_result) {
                $controller_path = $match_result->route()->getController();
                $controller = new $controller_path($this->_context);

                if(!$controller->routes_request_auth) {
                    throw new Exception("$controller_path must be implement route definitions");
                }

                if(!array_key_exists($match_result->route()->getName(), $controller->routes_request_auth)) {
                    throw new Exception("$controller_path must be implement route definition for {$match_result->route()->getName()}");
                }

                if($controller->routes_request_auth[$match_result->route()->getName()] && !$this->getContext()->auth()->isAuth()) {
                    if($this->_context->request()->cookie()->has($this->_context->getConfig()->getAll()["first_connection_cookiekey"]))
                        $this->_context->request()->flash()->push("errors", "You must be logged.");
                    return new RedirectResponse("/auth/login");
                } elseif(!$controller->routes_request_auth[$match_result->route()->getName()] && $this->getContext()->auth()->isAuth()) {
                    $this->_context->request()->flash()->push("errors", "You must be logout.");
                    return new RedirectResponse("/dashboard/index");
                }

                try {
                    $method_args = array_reduce((new ReflectionMethod($controller, $match_result->route()->getAction()))->getParameters(), function($a, $v) use ($match_result) {
                        /**
                         * @var ReflectionParameter $v
                         */
                        if(array_key_exists($v->name, $match_result->routeParameters())) {
                            $a[] = $match_result->routeParameters()[$v->name];
                        } else {
                            $a[] = null;
                        }
                        return $a;
                    }, []);
                } catch (ReflectionException $e) {
                    die($e->getMessage());
                }

                $response =  call_user_func_array([$controller, $match_result->route()->getAction()], $method_args);

                return ($response) ? $response : new EmptyResponse();
            }
        }

        return new Response($this->_context, "errors/404");
    }

    public function getContext(): ApplicationContext
    {
        return $this->_context;
    }
}
