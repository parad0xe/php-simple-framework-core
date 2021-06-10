<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder\Query;


use Exception;
use PDO;
use PDOStatement;

class QueryUpdate extends AbstractQuery
{
    /**
     * @var array
     */
    private array $update;

    /**
     * @var array
     */
    private array $data;

    private int $parameter_index = 0;

    public function __construct(array $update, PDO $pdo, string $table, ?string $entity_classname = null)
    {
        parent::__construct($pdo, $table, $entity_classname);

        $this->update = $update;
        $this->type = self::$TYPE_UPDATE;
    }

    /**
     * @param array ...$data
     * @return bool
     * @throws Exception
     */
    public function persist(array ...$data): bool {
        $this->data = $data;

        $query = $this->getQuery();

        return $this->executeQuery($query);
    }

    public function getQuery(): PDOStatement {
        $this->__pushWhere();

        $set = array_reduce($this->data, function($query, $v) {
            if(!is_array($v) || count($v) !== count($this->update))
                throw new Exception("Invalid update query");

            foreach ($this->update as $i => $parameter) {
                $key = "{$parameter}_{$this->parameter_index}";
                $query[] = "$parameter = :$key";
                $this->setParameter($key, $v[$i]);
            }

            $this->parameter_index++;
            return $query;
        }, []);

        $values = implode(", ", $set);
        $where = (count($this->where) > 0) ? " WHERE " . implode(" OR ", $this->where) : "";

        $statement = "UPDATE {$this->table} SET {$values}{$where}";

        $query = $this->pdo->prepare($statement);
        foreach ($this->parameters as $key => $value)
            $query->bindValue($key, $value);

        $this->clear();

        return $query;
    }

    public function clear()
    {
        $this->__clear();
        $this->data = [];
        $this->parameter_index = 0;
    }
}
