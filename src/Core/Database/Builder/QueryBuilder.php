<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder;


use PDO;
use PDOStatement;

class QueryBuilder
{
    private static string $TYPE_SELECT = "SELECT";

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
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $select;

    /**
     * @var array
     */
    private array $where = [];

    /**
     * @var array
     */
    private array $active_where = [];

    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * @var array
     */
    private array $limit = [];

    /**
     * @var array
     */
    private array $order_by = [];

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
        $this->select = "*";
        $this->type = self::$TYPE_SELECT;
    }

    /**
     * @param $select
     * @return QueryBuilder
     */
    public function select($select): QueryBuilder {
        $this->select = $select;
        $this->type = self::$TYPE_SELECT;
        return $this;
    }

    /**
     * @param string $string_query
     * @return QueryBuilder
     */
    public function where(string $string_query): QueryBuilder {
        $this->active_where[] = $string_query;
        return $this;
    }

    /**
     * @param string $string_query
     * @return QueryBuilder
     */
    public function andWhere(string $string_query): QueryBuilder {
        $this->where($string_query);
        return $this;
    }

    /**
     * @param string $string_query
     * @return QueryBuilder
     */
    public function orWhere(string $string_query): QueryBuilder {
        $this->__pushWhere();
        $this->where($string_query);
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return QueryBuilder
     */
    public function setParameter(string $key, $value): QueryBuilder {
        $this->parameters[$key] = $value;
        return $this;
    }

    /**
     * @param array $parameters
     * @return QueryBuilder
     */
    public function setParameters(array $parameters): QueryBuilder {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param string $field
     * @param string $mode
     * @return QueryBuilder
     */
    public function orderBy(string $field, string $mode): QueryBuilder {
        $mode = strtoupper($mode);

        $this->order_by[] = "$field $mode";
        return $this;
    }

    /**
     * @param int $limit
     * @param int|null $offset
     * @return QueryBuilder
     */
    public function limit(int $limit, ?int $offset = null): QueryBuilder {
        $this->limit = [$limit, $offset];
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function findOne()
    {
        $query = $this->getQuery();
        $query->execute();
        $res = $query->fetch();

        return ($res !== false) ? $res : null;
    }

    /**
     * @return array
     */
    public function findAll(): array {
        $query = $this->getQuery();
        $query->execute();
        return $query->fetchAll();
    }

    public function getQuery(): PDOStatement {
        $this->__pushWhere();

        $where = (count($this->where) > 0) ? " WHERE " . implode(" OR ", $this->where) : "";
        $order = (count($this->order_by) > 0) ? " ORDER BY " . implode(", ", $this->order_by) : "";
        $limit = (count($this->limit) > 0)
            ? ($this->limit[1])
                ? " LIMIT {$this->limit[0]} OFFSET {$this->limit[1]}"
                : " LIMIT {$this->limit[0]}"
            : "";

        $statement = "";
        switch ($this->type) {
            case self::$TYPE_SELECT:
                $statement = "SELECT {$this->select} FROM {$this->table}{$where}{$order}{$limit}";
                break;
        }

        $query = $this->pdo->prepare($statement);
        foreach ($this->parameters as $key => $value)
            $query->bindValue($key, $value);

        if($this->entity_classname !== null)
            $query->setFetchMode(PDO::FETCH_CLASS, $this->entity_classname);

        $this->__clear();

        return $query;
    }

    private function __pushWhere() {
        if(count($this->active_where) > 0) {
            $this->where[] = "(" . implode(" AND ", $this->active_where) . ")";
            $this->active_where = [];
        }
    }

    private function __clear() {
        $this->type = self::$TYPE_SELECT;
        $this->select = "*";
        $this->where = [];
        $this->active_where = [];
        $this->parameters = [];
        $this->limit = [];
        $this->order_by = [];
    }
}
