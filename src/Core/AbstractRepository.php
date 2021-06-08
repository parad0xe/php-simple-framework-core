<?php


namespace Parad0xeSimpleFramework\Core;


class AbstractRepository
{
    /**
     * @var ApplicationContext
     */
    protected $context;

    /**
     * UserRepository constructor.
     * @param ApplicationContext $context
     */
    public function __construct(ApplicationContext $context)
    {
        $this->context = $context;
    }
}
