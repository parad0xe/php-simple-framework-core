<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder\Query;


use Exception;
use PDO;
use PDOStatement;

class QueryInsert extends AbstractQuery
{
    /**
     * @var array
     */
    private array $insert;

    /**
     * @var array
     */
    private array $data;

    private int $parameter_index = 0;

    public function __construct(array $insert, PDO $pdo, string $table, ?string $entity_classname = null)
    {
        parent::__construct($pdo, $table, $entity_classname);

        $this->insert = $insert;
        $this->type = self::$TYPE_INSERT;
    }

    /**
     * Return last insert ID
     * @param array ...$data
     * @return int|null
     * @throws Exception
     */
    public function persist(array ...$data): ?int
    {
        $this->data = $data;

        $query = $this->getQuery();

        if ($this->executeQuery($query)) {
            $query = $this->pdo->query("SELECT LAST_INSERT_ID() FROM {$this->table}");
            $this->executeQuery($query);
            return $query->fetchColumn();
        }

        return null;
    }

    public function getQuery(): PDOStatement
    {
        $insert = array_reduce($this->data, function ($query, $v) {
            if (!is_array($v) || count($v) !== count($this->insert))
                throw new Exception("Invalid insert query");

            $query_part = [];
            foreach ($this->insert as $i => $parameter) {
                $key = "{$parameter}_{$this->parameter_index}";
                $query_part[] = ":$key";

                $this->setParameter($key, $v[$i]);
            }

            $query[] = "(" . implode(", ", $query_part) . ")";

            $this->parameter_index++;
            return $query;
        }, []);

        $column = implode(", ", $this->insert);
        $values = implode(", ", $insert);

        $statement = "INSERT INTO {$this->table} ($column) VALUES $values";

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
