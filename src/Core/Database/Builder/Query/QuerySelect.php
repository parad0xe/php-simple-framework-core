<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder\Query;


use Exception;
use Parad0xeSimpleFramework\Core\Database\Builder\QueryBuilderTrait;
use PDO;
use PDOStatement;

class QuerySelect extends AbstractQuery
{
    /**
     * @var string
     */
    private string $select;

    /**
     * @var string|null
     */
    private ?string $alias = null;

    /**
     * @var array
     */
    protected array $join = [];

    /**
     * @var array
     */
    protected array $limit = [];
    /**
     * @var array
     */
    protected array $order_by = [];

    public function __construct(string $select, PDO $pdo, string $table, ?string $entity_classname = null)
    {
        parent::__construct($pdo, $table, $entity_classname);

        $this->select = $select;
        $this->type = self::$TYPE_SELECT;
    }

    /**
     * @param string $alias
     * @return QuerySelect
     */
    public function alias(string $alias): QuerySelect {
        $this->alias = $alias;
        return $this;
    }

    public function join(string $table, string $alias, ?string $join_condition = null): QuerySelect {
        if(!$join_condition) {
            $join_condition = $alias;
            $alias = null;
        }

        $table = ($alias) ? "$table as $alias" : $table;
        $this->join[] = "INNER JOIN $table ON $join_condition";

        return $this;
    }

    /**
     * @param string $field
     * @param string $mode
     * @return QuerySelect
     */
    public function orderBy(string $field, string $mode): QuerySelect {
        $mode = strtoupper($mode);

        $this->order_by[] = "$field $mode";
        return $this;
    }

    /**
     * @param int $limit
     * @param int|null $offset
     * @return QuerySelect
     */
    public function limit(int $limit, ?int $offset = null): QuerySelect {
        $this->limit = [$limit, $offset];
        return $this;
    }

    /**
     * @return mixed|null
     * @throws Exception
     */
    public function findOne()
    {
        $query = $this->getQuery();

        if($this->executeQuery($query)) {
            $res = $query->fetch();
            return ($res !== false) ? $res : null;
        }

        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function findAll(): array {
        $query = $this->getQuery();

        if($this->executeQuery($query))
            return $query->fetchAll();
        return [];
    }

    public function getQuery(): PDOStatement {
        $this->__pushWhere();

        $table = ($this->alias) ? "{$this->table} as {$this->alias}" : "{$this->table}";
        $join = (count($this->join) > 0) ? " " . implode(" ", $this->join) : "";
        $where = (count($this->where) > 0) ? " WHERE " . implode(" OR ", $this->where) : "";
        $order = (count($this->order_by) > 0) ? " ORDER BY " . implode(", ", $this->order_by) : "";
        $limit = (count($this->limit) > 0)
            ? ($this->limit[1])
                ? " LIMIT {$this->limit[0]} OFFSET {$this->limit[1]}"
                : " LIMIT {$this->limit[0]}"
            : "";

        $statement = "SELECT {$this->select} FROM {$table}{$join}{$where}{$order}{$limit}";

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
        $this->limit = [];
        $this->order_by = [];
    }
}
