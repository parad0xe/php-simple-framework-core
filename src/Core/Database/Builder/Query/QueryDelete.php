<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder\Query;


use Exception;
use PDO;
use PDOException;
use PDOStatement;

class QueryDelete extends AbstractQuery
{
    use WhereProtection;

    public function __construct(PDO $pdo, string $table, ?string $entity_classname = null)
    {
        parent::__construct($pdo, $table, $entity_classname);

        $this->type = self::$TYPE_DELETE;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute(): bool {
        $query = $this->getQuery();
        return $this->executeQuery($query);
    }

    /**
     * @return PDOStatement
     * @throws Exception
     */
    public function getQuery(): PDOStatement
    {
        $this->__pushWhere();

        if(count($this->where) === 0 && $this->where_protection)
            throw new Exception("[WHERE protection activate] Delete query failed. No WHERE added to query.");

        $where = (count($this->where) > 0) ? " WHERE " . implode(" OR ", $this->where) : "";

        $statement = "DELETE FROM {$this->table}{$where}";

        $query = $this->pdo->prepare($statement);
        foreach ($this->parameters as $key => $value)
            $query->bindValue($key, $value);

        if($this->entity_classname !== null)
            $query->setFetchMode(PDO::FETCH_CLASS, $this->entity_classname);

        $this->clear();

        return $query;
    }

    public function clear()
    {
        $this->__clear();
    }
}
