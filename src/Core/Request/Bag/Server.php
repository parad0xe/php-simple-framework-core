<?php

namespace Parad0xeSimpleFramework\Core\Request\Bag;

use Parad0xeSimpleFramework\Core\Request\RequestBag;

class Server extends RequestBag
{
    public function uri(): ?string
    {
        return $this->get("REQUEST_URI");
    }

    public function method(): ?string
    {
        return $this->get("REQUEST_METHOD");
    }

    public function protocol(): ?string
    {
        return $this->get("SERVER_PROTOCOL");
    }

    public function port(): ?string
    {
        return $this->get("SERVER_PORT");
    }
}
