<?php
namespace Parad0xeSimpleFramework\Core\Request;

class Request
{
    use RequestTrait;

    public function uri(): ?string {
        return $this->server()->uri();
    }

    public function method(): ?string {
        return strtoupper($this->server()->method());
    }

    public function protocol(): ?string {
        return $this->server()->protocol();
    }
}
