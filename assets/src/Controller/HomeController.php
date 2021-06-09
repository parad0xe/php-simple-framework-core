<?php


namespace App\Controller;

use Parad0xeSimpleFramework\Core\Route\Route;
use Parad0xeSimpleFramework\Core\Route\RouteMethod;
use Parad0xeSimpleFramework\Core\Http\Controller\AbstractController;

class HomeController extends AbstractController
{
    public ?array $routes_request_auth = [
        "home:index" => false,
        "home:display" => false,
        "home:routes" => false,
    ];

    #[Route("home:index", "/home")]
    public function index() {
        return $this->render("home/index");
    }

    #[Route("home:display", "/home/display/:id", ["id" => ["default" => 1, "regex" => "\d+"]])]
    #[RouteMethod("get", "post")]
    public function display(int $id) {
        return $this->json([
            "id" => $id
        ]);
    }

    #[Route("home:routes", "/home/routes")]
    public function displayRoutes() {
        $routes = array_map(function(Route $route) {
            return $route->getName();
        }, $this->_context->route()->all());

        return $this->json([
            "routes" => array_values($routes)
        ]);
    }
}
