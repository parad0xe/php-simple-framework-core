<?php


namespace Parad0xeSimpleFramework\Core;

use Exception;
use stdClass;

class Configuration
{
    private string $_root_project_directory;

    private array $_configuration = [];

    /**
     * Configuration constructor.
     * @param $root_project_directory
     * @throws Exception
     */
    public function __construct($root_project_directory)
    {
        $this->_root_project_directory = $root_project_directory;

        if(!file_exists($root_project_directory . '/config/framework.yml')) {
            throw new Exception("Missing '$root_project_directory/config/framework.yml' file.");
        }

        $res = \yaml_parse_file($root_project_directory . '/config/framework.yml');
        $this->__parseConfig($res);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if(array_key_exists($key, $this->_configuration))
            return $this->_configuration[$key];

        return null;
    }

    private function __parseConfig($data, $base_key = "") {
        foreach ($data as $k => $v) {
            $current_key = ($base_key === "") ? $k : "$base_key.$k";

            if(is_array($v)) {
                $this->__parseConfig($v, $current_key);
            } else {
                $v = str_replace("%root%", $this->_root_project_directory, $v);

                $this->_configuration[$current_key] = $v;
            }
        }
    }
}
