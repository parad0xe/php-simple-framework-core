<?php

namespace Parad0xeSimpleFramework\Core\Request;

abstract class RequestBag implements RequestBagInterface
{
    /**
     * @var string
     */
    protected ?string $app_id = null;

    /**
     * @var array
     */
    protected array $data;

    /**
     * RequestBag constructor.
     * @param array $data
     */
    public function __construct(array &$data)
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
}
