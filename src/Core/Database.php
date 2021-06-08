<?php

namespace Parad0xeSimpleFramework\Core;

use PDO;
use stdClass;

class Database
{
    /**
     * @var PDO
     */
    private ?PDO $pdo = null;

    /**
     * @var stdClass
     */
    private stdClass $config;

    /**
     * BDD constructor.
     * @param ApplicationContext $context
     */
    public function __construct($context)
    {
        $this->config = $context->getConfig()->getDatabaseConfig();

        if($this->config->connect_database) {
            $this->pdo = new PDO("mysql:dbname={$this->config->database};host={$this->config->host};port={$this->config->port}", $this->config->user, $this->config->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        }
    }

    /**
     * @return PDO|null
     */
    public function pdo()
    {
        return $this->pdo;
    }
}
