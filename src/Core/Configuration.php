<?php


namespace Parad0xeSimpleFramework\Core;

use Exception;
use stdClass;

class Configuration
{
    /**
     * @var array
     */
    private $_config;

    /**
     * Configuration constructor.
     * @param $root_directory
     * @throws Exception
     */
    public function __construct($root_directory)
    {
        if(!file_exists($root_directory . '/app_configuration.php')) {
            throw new Exception("Missing 'app_configuration.php' file.");
        }

        /** @noinspection PhpIncludeInspection */
        $this->_config = require_once($root_directory . '/app_configuration.php');

        foreach ($this->_config["endpoints"] as $k => $endpoint)
            $this->_config["endpoints"][$k] = "/" . trim($endpoint, "/");
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->_config["app_root_dir"];
    }

    /**
     * @return string
     */
    public function getPublicDir()
    {
        return $this->_config["app_public_dir"];
    }

    /**
     * @return string
     */
    public function getPagesDir()
    {
        return $this->_config["app_page_dir"];
    }

    /**
     * @return array
     */
    public function getEndpoints() {
        return $this->_config["endpoints"];
    }

    /**
     * @return stdClass
     */
    public function getDatabaseConfig() {
        $o = new stdClass();

        foreach ($this->_config["database"] as $db_conf_key => $db_conf_value)
            $o->$db_conf_key = $db_conf_value;

        return $o;
    }

    /**
     * @return array
     */
    public function getAll() {
        return $this->_config;
    }
}
