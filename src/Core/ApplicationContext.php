<?php


namespace Parad0xeSimpleFramework\Core;

use Exception;
use Parad0xeSimpleFramework\Core\Database\Database;
use Parad0xeSimpleFramework\Core\Request\Request;
use Parad0xeSimpleFramework\Core\Route\RouteMap;
use PDO;

class ApplicationContext
{
    /**
     * @var string
     */
    private string $_root_directory;

    /**
     * @var Auth
     */
    private Auth $_auth;

    /**
     * @var Request
     */
    private Request $_request;

    /**
     * @var Database
     */
    private Database $_database;

    /**
     * @var Configuration
     */
    private Configuration $_config;
    /**
     * @var RouteMap
     */
    private RouteMap $_route_map;

    /**
     * ApplicationContext constructor.
     * @param string $root_directory
     * @param Request $request
     * @throws Exception
     */
    public function __construct(string $root_directory, Request $request)
    {
        $this->_root_directory = $root_directory;
        $this->_config = new Configuration($this->_root_directory);
        $this->_auth = new Auth($request);
        $this->_request = $request;
        $this->_database = new Database($this);
        $this->_route_map = new RouteMap($this);
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return $this->_request;
    }

    /**
     * @return Auth
     */
    public function auth(): Auth
    {
        return $this->_auth;
    }

    /**
     * @return Database|null
     */
    public function database()
    {
        return $this->_database;
    }

    /**
     * @return Configuration
     */
    public function config() {
        return $this->_config;
    }

    /**
     * @return RouteMap
     */
    public function route() {
        return $this->_route_map;
    }
}
