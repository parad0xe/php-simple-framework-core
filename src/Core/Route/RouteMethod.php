<?php


namespace Parad0xeSimpleFramework\Core\Route;


class RouteMethod
{
    static string $GET = "GET";
    static string $POST = "POST";
    static string $DELETE = "DELETE";
    static string $PUT = "PUT";
    static string $PATCH = "PATCH";

    private array $methods;

    public function __construct(...$methods)
    {
        $this->methods = $methods;
    }

    public function getMethods(): array {
        return $this->methods;
    }
}
