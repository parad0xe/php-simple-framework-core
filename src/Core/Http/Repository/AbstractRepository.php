<?php


namespace Parad0xeSimpleFramework\Core\Http\Repository;


use Exception;
use Parad0xeSimpleFramework\Core\ApplicationContext;
use Parad0xeSimpleFramework\Core\Database\Builder\QueryBuilder;

abstract class AbstractRepository
{
    protected string $table;
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
     * @param string $table
     * @param string $entity_classname
     * @throws Exception
     */
    public function __construct(ApplicationContext $context, string $table, ?string $entity_classname = null)
    {
        $this->context = $context;
        $this->table = $table;
        $this->entity_classname = $entity_classname;

        $this->builder = $context->database()->builder($this->table, $this->entity_classname);
    }
}
