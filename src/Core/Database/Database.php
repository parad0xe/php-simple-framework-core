<?php

namespace Parad0xeSimpleFramework\Core\Database;

use Exception;
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
     * BDD constructor.
     * @param ApplicationContext $context
     */
    public function __construct($context)
    {
        $database = $context->config()->get("app.database.database");
        $host = $context->config()->get("app.database.host");
        $user = $context->config()->get("app.database.user");
        $password = $context->config()->get("app.database.password");
        $port = $context->config()->get("app.database.post");
        $connect_database = $context->config()->get("app.database.connect_database");

        if($connect_database) {
            $this->pdo = new PDO("mysql:dbname={$database};host={$host};port={$port}", $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        }
    }

    /**
     * @param string $table
     * @param string|null $entity_classname
     * @return QueryBuilder
     * @throws Exception
     */
    public function builder(string $table, ?string $entity_classname = null): QueryBuilder
    {
        if(!$this->pdo) {
            throw new Exception("No database connection");
        }

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
