<?php


namespace Parad0xeSimpleFramework\Core\Request\Bag;


use Parad0xeSimpleFramework\Core\Request\RequestBag;

class Session extends RequestBag
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
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value) {
        $_SESSION["{$this->_app_id}.$key"] = $value;
    }

    public function has(string $key): bool
    {
        return parent::has("{$this->_app_id}.$key");
    }

    public function get(string $key, $default = null)
    {
        return parent::get("{$this->_app_id}.$key", $default);
    }

    /**
     * @param string $key
     */
    public function unset(string $key)
    {
        if($this->has("{$this->_app_id}.$key")) {
            unset($_SESSION["{$this->_app_id}.$key"]);
        }
    }
}
