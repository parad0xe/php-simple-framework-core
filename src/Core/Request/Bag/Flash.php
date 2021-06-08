<?php


namespace Parad0xeSimpleFramework\Core\Request\Bag;


use Parad0xeSimpleFramework\Core\Request\RequestBag;

class Flash extends RequestBag
{
    /**
     * Add key => value in session
     *
     * @param string $key
     * @param $value
     */
    public function add(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Add value in array of values
     *
     * @param string $key
     * @param $value
     */
    public function push(string $key, $value)
    {
        if(!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }

        $_SESSION[$key][] = $value;
    }

    /**
     * Retrieve value once
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        if(!isset($_SESSION[$key])) return $default;

        $data = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $data;
    }
}
