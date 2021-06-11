<?php

namespace Parad0xeSimpleFramework\Core;

use Parad0xeSimpleFramework\Core\Auth\Auth;
use Parad0xeSimpleFramework\Core\Http\Uri\Uri;
use Parad0xeSimpleFramework\Core\Http\Uri\UriMatcher;
use Parad0xeSimpleFramework\Core\Request\Request;
use Parad0xeSimpleFramework\Core\Response\EmptyResponse;
use Parad0xeSimpleFramework\Core\Response\ErrorResponse;
use Parad0xeSimpleFramework\Core\Response\RedirectResponse;
use Parad0xeSimpleFramework\Core\Response\Response;
use Parad0xeSimpleFramework\Core\Response\ResponseInterface;
use Exception;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Throwable;

class Application
{
    /**
     * @var string
     */
    protected string $_root_project_directory;

    /**
     * @var ApplicationContext
     */
    protected ApplicationContext $_context;

    public function __construct($root_project_directory)
    {
        $this->_root_project_directory = rtrim($root_project_directory, "/");

        require_once __DIR__ . '/../../macro_functions.php';

        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $this->_context = new ApplicationContext($this->_root_project_directory);
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function dispatch(Request $request): ResponseInterface
    {
        $this->_context->load($request);

        $uri = new Uri($request->uri());

        foreach ($this->_context->route()->all() as $route) {
            $match_result = (new UriMatcher($this->_context))->match($uri, $route);

            if ($match_result) {
                $controller_path = $match_result->route()->getController();

                if ($match_result->route()->getName() === "root")
                    return new RedirectResponse($this->_context->route()->get($this->_context->config()->get("app.endpoints.home"))->getUri());

                if (!class_exists($controller_path))
                    return new ErrorResponse($this->_context, 404);

                $controller = new $controller_path($this->_context);

                if (!$controller->routes_request_auth)
                    return new ErrorResponse($this->_context, 500, "'$controller_path' must implement route definitions");

                if (!array_key_exists($match_result->route()->getName(), $controller->routes_request_auth))
                    return new ErrorResponse($this->_context, 500, "'$controller_path' must implement route definition for '{$match_result->route()->getName()}'");

                if ($controller->routes_request_auth[$match_result->route()->getName()] && !$this->getContext()->auth()->isAuth()) {
                    if($this->_context->config()->get("app.endpoints.auth.login") === $match_result->route()->getName())
                        return new ErrorResponse($this->_context, 500, "infinite redirection for route '{$match_result->route()->getName()}' (login required but not connected)");
                    if ($this->_context->request()->cookie()->has($this->_context->config()->get("app.cookie.first_connection")))
                        $this->_context->request()->flash()->push("errors", "You must be logged.");
                    return new RedirectResponse($this->_context->route()->get($this->_context->config()->get("app.endpoints.auth.login"))->getUri());
                } elseif (!$controller->routes_request_auth[$match_result->route()->getName()] && $this->getContext()->auth()->isAuth()) {
                    if($this->_context->config()->get("app.endpoints.home") === $match_result->route()->getName())
                        return new ErrorResponse($this->_context, 500, "infinite redirection for route '{$match_result->route()->getName()}' (logout required but connected)");
                    $this->_context->request()->flash()->push("errors", "You must be logout.");
                    return new RedirectResponse($this->_context->route()->get($this->_context->config()->get("app.endpoints.home"))->getUri());
                }

                $di_container = [
                    ApplicationContext::class => $this->_context,
                    Request::class => $this->_context->request(),
                    Auth::class => $this->_context->auth()
                ];

                try {
                    $method_args = array_reduce((new ReflectionMethod($controller, $match_result->route()->getAction()))->getParameters(), function ($a, $v) use ($match_result, $di_container) {
                        /**
                         * @var ReflectionParameter $v
                         */
                        if (array_key_exists($v->name, $match_result->routeParameters())) {
                            $a[] = $match_result->routeParameters()[$v->name];
                        } else if($v->getType() && array_key_exists($v->getType()->getName(), $di_container)) {
                            $a[] = $di_container[$v->getType()->getName()];
                        } else {
                            $a[] = null;
                        }
                        return $a;
                    }, []);
                } catch (ReflectionException $e) {
                    return new ErrorResponse($this->_context, 500, $e->getMessage());
                }

                try {
                    $response = call_user_func_array([$controller, $match_result->route()->getAction()], $method_args);
                } catch (Throwable $e) {
                    return new ErrorResponse($this->_context, 500, $e->getMessage());
                }

                return ($response) ? $response : new EmptyResponse();
            }
        }

        return new ErrorResponse($this->_context, 404);
    }

    public function getContext(): ApplicationContext
    {
        return $this->_context;
    }
}
