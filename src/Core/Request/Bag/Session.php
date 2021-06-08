<?php


namespace Parad0xeSimpleFramework\Core\Request\Bag;


use Parad0xeSimpleFramework\Core\Request\RequestBag;

class Session extends RequestBag
{
    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function unset(string $key)
    {
        if($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }
}
