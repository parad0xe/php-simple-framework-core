<?php

namespace Parad0xeSimpleFramework\Core\Database;

use Parad0xeSimpleFramework\Core\ApplicationContext;
use Parad0xeSimpleFramework\Core\Database\Builder\QueryBuilder;
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
        $this->config = $context->config()->getDatabaseConfig();

        if($this->config->connect_database) {
            $this->pdo = new PDO("mysql:dbname={$this->config->database};host={$this->config->host};port={$this->config->port}", $this->config->user, $this->config->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        }
    }

    /**
     * @param string $table
     * @param string|null $entity_classname
     * @return QueryBuilder
     */
    public function builder(string $table, ?string $entity_classname = null): QueryBuilder
    {
        return new QueryBuilder($this->pdo(), $table, $entity_classname);
    }

    /**
     * @return PDO|null
     */
    public function pdo()
    {
        return $this->pdo;
    }
}
