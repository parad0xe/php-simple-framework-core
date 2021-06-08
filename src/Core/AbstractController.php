<?php


namespace Parad0xeSimpleFramework\Core;


use Parad0xeSimpleFramework\Core\Response\JsonResponse;
use Parad0xeSimpleFramework\Core\Response\RedirectResponse;
use Parad0xeSimpleFramework\Core\Response\Response;

abstract class AbstractController
{
    /**
     * @var array|null
     */
    public ?array $routes_request_auth = null;

    /**
     * @var ApplicationContext
     */
    protected ApplicationContext $_context;

    public function __construct(ApplicationContext $context)
    {
        $this->_context = $context;
    }

    /**
     * @param string $page
     * @param array $args
     * @return Response
     */
    protected function render(string $page, array $args = []): Response {
        return new Response($this->_context, $page, $args);
    }

    /**
     * @param array $args
     * @return JsonResponse
     */
    protected function json(array $args = []): JsonResponse {
        return new JsonResponse($args);
    }

    /**
     * @param string $route_name
     * @param array $params
     * @return RedirectResponse
     */
    protected function redirectTo(string $route_name, array $params = []) {
        return $this->_context->route()->redirectTo($route_name, $params);
    }
}
