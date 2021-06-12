<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder;


use Parad0xeSimpleFramework\Core\Database\Builder\Query\QueryDelete;
use Parad0xeSimpleFramework\Core\Database\Builder\Query\QueryInsert;
use Parad0xeSimpleFramework\Core\Database\Builder\Query\QuerySelect;
use Parad0xeSimpleFramework\Core\Database\Builder\Query\QueryUpdate;
use PDO;

class QueryBuilder
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @var string
     */
    private string $table;

    /**
     * @var string|null
     */
    private ?string $entity_classname;

    /**
     * QueryBuilder constructor.
     * @param PDO $pdo
     * @param string $table
     * @param string|null $entity_classname
     */
    public function __construct(PDO $pdo, string $table, ?string $entity_classname = null)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->entity_classname = $entity_classname;
    }

    /**
     * @param string $select
     * @return QuerySelect
     */
    public function select(string $select): QuerySelect {
        return new QuerySelect($select, $this->pdo, $this->table, $this->entity_classname);
    }

    /**
     * @param array $targets
     * @return QueryInsert
     */
    public function insert(...$targets): QueryInsert {
        return new QueryInsert($targets, $this->pdo, $this->table, $this->entity_classname);
    }

    /**
     * @param array $targets
     * @return QueryUpdate
     */
    public function update(...$targets): QueryUpdate {
        return new QueryUpdate($targets, $this->pdo, $this->table, $this->entity_classname);
    }

    /**
     * @return QueryDelete
     */
    public function delete(): QueryDelete {
        return new QueryDelete($this->pdo, $this->table, $this->entity_classname);
    }
}
