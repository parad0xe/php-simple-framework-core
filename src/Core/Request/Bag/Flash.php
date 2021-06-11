<?php


namespace Parad0xeSimpleFramework\Core\Request\Bag;


use Parad0xeSimpleFramework\Core\Request\RequestBag;

class Flash extends RequestBag
{
    /**
     * @var string
     */
    private string $_app_id;

    public function __construct(string $app_id, array &$data)
    {
        parent::__construct($data);
        $this->_app_id = $app_id;
    }

    /**
     * Add key => value in session
     *
     * @param string $key
     * @param $value
     */
    public function add(string $key, $value)
    {
        $_SESSION["{$this->_app_id}.$key"] = $value;
    }

    /**
     * Add value in array of values
     *
     * @param string $key
     * @param $value
     */
    public function push(string $key, $value)
    {
        if(!isset($_SESSION["{$this->_app_id}.$key"])) {
            $_SESSION["{$this->_app_id}.$key"] = [];
        }

        $_SESSION["{$this->_app_id}.$key"][] = $value;
    }

    public function has(string $key): bool
    {
        return parent::has("{$this->_app_id}.$key");
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
        if(!isset($_SESSION["{$this->_app_id}.$key"])) return $default;

        $data = $_SESSION["{$this->_app_id}.$key"];
        unset($_SESSION["{$this->_app_id}.$key"]);
        return $data;
    }
}
