<?php


namespace App\Controller;

use Parad0xeSimpleFramework\Core\Route\Route;
use Parad0xeSimpleFramework\Core\Http\Controller\AbstractController;

class HomeController extends AbstractController
{
    public ?array $routes_request_auth = [
        "home:index" => false
    ];

    #[Route("home:index", "/home")]
    public function index() {
        return $this->render("home/index");
    }
}
