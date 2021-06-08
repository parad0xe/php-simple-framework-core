<?php


namespace Parad0xeSimpleFramework\Core\Response;


class RedirectResponse implements ResponseInterface
{
    public function __construct($uri)
    {
        header("Location: $uri");
        die;
    }

    public function render(): string
    {
        return "";
    }
}
