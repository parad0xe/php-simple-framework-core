<?php


namespace Parad0xeSimpleFramework\Core\Request\Bag;


use Parad0xeSimpleFramework\Core\Request\RequestBag;

class Cookie extends RequestBag
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
        setcookie("{$this->_app_id}.$key", $value, 0, "/");
    }

    public function get(string $key, $default = null)
    {
        return parent::get("{$this->_app_id}.$key", $default);
    }

    public function has(string $key): bool
    {
        return parent::has("{$this->_app_id}.$key");
    }

    /**
     * @param string $key
     */
    public function delete(string $key) {
        if($this->has($key)) {
            unset($_COOKIE["{$this->_app_id}.$key"]);
        }
    }
}
