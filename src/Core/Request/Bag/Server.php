<?php

namespace Parad0xeSimpleFramework\Core\Request\Bag;

use Parad0xeSimpleFramework\Core\Request\RequestBag;

class Server extends RequestBag
{
    /**
     * @return string
     */
    public function uri(): string
    {
        return $this->get("REQUEST_URI");
    }
}
