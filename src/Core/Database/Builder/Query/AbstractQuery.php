<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder\Query;


use Exception;
use Parad0xeSimpleFramework\Core\Database\Builder\QueryBuilderTrait;
use PDO;
use PDOException;
use PDOStatement;

abstract class AbstractQuery implements QueryInterface
{
    public static string $TYPE_SELECT = "SELECT";
    public static string $TYPE_UPDATE = "UPDATE";
    public static string $TYPE_DELETE = "DELETE";
    public static string $TYPE_INSERT = "INSERT";

    use QueryBuilderTrait {
        QueryBuilderTrait::__construct as protected __qbConstruct;
    }

    protected string $type;

    public function __construct(PDO $pdo, string $table, ?string $entity_classname = null)
    {
        $this->__qbConstruct($pdo, $table, $entity_classname);
    }

    /**
     * @param PDOStatement $query
     * @return bool
     * @throws Exception
     */
    public function executeQuery(PDOStatement $query): bool
    {
        try {
            $this->pdo->beginTransaction();
            $query->execute();
            $this->pdo->commit();
            return true;
        } catch(PDOException $e) {
            $this->pdo->rollback();
            throw new Exception($e->getMessage());
        }
    }
}
