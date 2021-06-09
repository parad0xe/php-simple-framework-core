<?php


namespace Parad0xeSimpleFramework\Core\Http\Repository;


use Exception;
use Parad0xeSimpleFramework\Core\ApplicationContext;
use Parad0xeSimpleFramework\Core\Database\Builder\QueryBuilder;

class AbstractRepository
{
    protected ?string $table = null;
    protected ?string $entity_classname = null;

    /**
     * @var ApplicationContext
     */
    protected ApplicationContext $context;

    /**
     * @var QueryBuilder
     */
    protected QueryBuilder $builder;

    /**
     * UserRepository constructor.
     * @param ApplicationContext $context
     * @throws Exception
     */
    public function __construct(ApplicationContext $context)
    {
        $this->context = $context;

        if(!$this->table) {
            throw new Exception("Repository " . __CLASS__ . " must implement \$table");
        }

        $this->builder = $context->database()->builder($this->table, $this->entity_classname);
    }
}
