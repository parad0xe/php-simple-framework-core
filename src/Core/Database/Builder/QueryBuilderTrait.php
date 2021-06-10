<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder;


use PDO;
use PDOStatement;

trait QueryBuilderTrait
{
    /**
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string|null
     */
    protected ?string $entity_classname;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var array
     */
    protected array $where = [];

    /**
     * @var array
     */
    protected array $active_where = [];

    /**
     * @var array
     */
    protected array $parameters = [];

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
     * @param string $string_query
     * @return self
     */
    public function where(string $string_query): self {
        $this->active_where[] = $string_query;
        return $this;
    }

    /**
     * @param string $string_query
     * @return self
     */
    public function andWhere(string $string_query): self {
        $this->where($string_query);
        return $this;
    }

    /**
     * @param string $string_query
     * @return self
     */
    public function orWhere(string $string_query): self {
        $this->__pushWhere();
        $this->where($string_query);
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return self
     */
    public function setParameter(string $key, $value): self {
        $this->parameters[$key] = $value;
        return $this;
    }

    /**
     * @param array $parameters
     * @return self
     */
    public function setParameters(array $parameters): self {
        $this->parameters = $parameters;
        return $this;
    }

    protected function __pushWhere() {
        if(count($this->active_where) > 0) {
            $this->where[] = "(" . implode(" AND ", $this->active_where) . ")";
            $this->active_where = [];
        }
    }

    protected function __clear() {
        $this->where = [];
        $this->active_where = [];
        $this->parameters = [];
    }
}
